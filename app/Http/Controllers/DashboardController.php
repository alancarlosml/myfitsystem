<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;
use App\Models\Exercise;
use App\Models\Establishment;
use App\Models\Category;
use App\Models\Student;
use App\Models\ClassBooking;
use App\Models\EstablishmentContracts;
use App\Models\ClassSchedule;
use App\Models\Modality;
use App\Models\PhysicalAssessment;
use App\Models\User;
use App\Models\StudentContracts;
use App\Models\Workout;
use App\Models\WorkoutLog;
use App\Services\DashboardService;
use App\Services\NotificationService;
use App\Services\GoalService;
use App\Services\AchievementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    protected $dashboardService;
    protected $notificationService;
    protected $goalService;
    protected $achievementService;

    public function __construct(
        DashboardService $dashboardService, 
        NotificationService $notificationService,
        GoalService $goalService,
        AchievementService $achievementService
    ) {
        $this->dashboardService = $dashboardService;
        $this->notificationService = $notificationService;
        $this->goalService = $goalService;
        $this->achievementService = $achievementService;
    }

    public function userDashboard()
    {
        $user = Auth::user();
        $establishmentId = Session::get('establishment_id');
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Verificar se é superuser primeiro (superuser pode não ter establishment_id)
        $superuserRole = \App\Models\Role::where('name', 'superuser')->first();
        $isSuperuser = false;
        
        if ($superuserRole) {
            $isSuperuser = DB::table('role_user')
                ->where('user_id', $user->id)
                ->where('role_id', $superuserRole->id)
                ->exists();
        }

        if ($isSuperuser) {
            $data = $this->dashboardService->getSuperuserDashboardData($currentMonth, $currentYear);
            $monthlyData = $this->dashboardService->getMonthlyRevenues(null, true);
            $data = array_merge($data, $monthlyData);
            return view('admin.dashboard-superuser', $data);
        } else {
            // Para usuários normais, pegar o role do establishment
            $role = $user->getRoleForEstablishment($establishmentId);
            $data = $this->dashboardService->getEstablishmentDashboardData($establishmentId, $currentMonth, $currentYear);
            $monthlyData = $this->dashboardService->getMonthlyRevenues($establishmentId, false);
            $data = array_merge($data, $monthlyData);
            return view('admin.dashboard', $data);
        }
    }

    public function studentDashboard()
    {
        $establishmentId = Session::get('establishment_id');
        $studentId = Auth::guard('student')->id();

        // Optimized: Get class counts in a single query
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $currentYearStart = now()->startOfYear();
        
        $classStats = ClassBooking::where('student_id', $studentId)
            ->where('created_at', '>=', $currentYearStart)
            ->selectRaw('
                COUNT(*) as total_year,
                SUM(CASE WHEN MONTH(created_at) = ? THEN 1 ELSE 0 END) as total_month
            ')
            ->addBinding($currentMonth, 'select')
            ->first();
        
        $classesThisMonth = $classStats->total_month ?? 0;
        $classesThisYear = $classStats->total_year ?? 0;

        // Calculate streak (consecutive weeks with at least 1 class)
        $streakDays = $this->calculateWorkoutStreak($studentId);

        // Check and award achievements based on current progress
        $newlyEarnedAchievements = $this->achievementService->checkAndAwardAchievements($studentId);
        
        // Get all achievements earned by student
        $studentAchievements = $this->achievementService->getStudentAchievements($studentId);
        $achievements = $studentAchievements->count();
        
        // Create notifications for newly earned achievements
        foreach ($newlyEarnedAchievements as $achievement) {
            $this->notificationService->notifyAchievementUnlocked(
                $studentId,
                $achievement->name
            );
        }

        // Last week's progress (optimized - combine with other queries if possible)
        $lastWeekClasses = ClassBooking::where('student_id', $studentId)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        // Get weekly goal from student_goals table
        $weeklyGoal = $this->goalService->getOrCreateWeeklyClassesGoal($studentId, $establishmentId, 4);
        $goalWeekly = $weeklyGoal->target_value;
        
        // Update goal progress
        $weeklyGoal = $this->goalService->updateWeeklyClassesProgress($studentId, $establishmentId);
        $weeklyProgress = $weeklyGoal->progress_percentage;
        
        // Get all active goals for display
        $activeGoals = $this->goalService->getActiveGoals($studentId, $establishmentId);
        
        // Check for newly achieved goals
        $achievedGoals = $this->goalService->checkAchievedGoals($studentId, $establishmentId);

        // Upcoming classes (next 3 days)
        $upcomingClasses = ClassBooking::with(['classSchedule.modality'])
            ->where('student_id', $studentId)
            ->whereHas('classSchedule', function($q) {
                $q->whereDate('class_date', '>=', now()->toDateString())
                  ->whereDate('class_date', '<=', now()->addDays(3)->toDateString());
            })
            ->orderBy('class_schedules.class_date')
            ->join('class_schedules', 'class_bookings.class_schedule_id', '=', 'class_schedules.id')
            ->select('class_bookings.*')
            ->limit(3)
            ->limit(3)
            ->get();

        // Get latest physical assessment
        $latestAssessment = PhysicalAssessment::where('student_id', $studentId)
            ->where('establishment_id', $establishmentId)
            ->orderBy('assessment_date', 'desc')
            ->first();

        // Get real notifications from database
        $notifications = $this->notificationService->getUnreadForStudent($studentId, 10);
        
        // Convert to array format for view compatibility
        $notificationsArray = $notifications->map(function($notification) {
            return [
                'id' => $notification->id,
                'type' => $notification->type,
                'title' => $notification->title,
                'message' => $notification->message,
                'action_url' => $notification->action_url,
                'created_at' => $notification->created_at,
            ];
        })->toArray();
        
        // Create notification for each newly achieved goal
        foreach ($achievedGoals as $achievedGoal) {
            $this->notificationService->notifyAchievementUnlocked(
                $studentId, 
                $achievedGoal->goal_name
            );
        }
        
        // Add dynamic notification if weekly goal is reached (create persistent notification)
        if ($lastWeekClasses >= $goalWeekly && $goalWeekly > 0) {
            // Check if notification already exists to avoid duplicates
            $existingNotification = \App\Models\Notification::where('student_id', $studentId)
                ->where('type', 'success')
                ->where('title', 'Meta semanal alcançada!')
                ->whereDate('created_at', today())
                ->first();
            
            if (!$existingNotification) {
                $this->notificationService->notifyWeeklyGoalAchievement($studentId, $lastWeekClasses, $goalWeekly);
            }
            
            // Also add to array for immediate display
            $notificationsArray[] = [
                'type' => 'success',
                'title' => 'Meta semanal alcançada!',
                'message' => "Parabéns! Você completou {$lastWeekClasses} de {$goalWeekly} aulas esta semana."
            ];
        }

        return view('student.dashboard', [
            'classesThisMonth' => $classesThisMonth,
            'classesThisYear' => $classesThisYear,
            'streakDays' => $streakDays,
            'achievements' => $achievements,
            'studentAchievements' => $studentAchievements,
            'weeklyProgress' => $weeklyProgress,
            'goalWeekly' => $goalWeekly,
            'lastWeekClasses' => $lastWeekClasses,
            'activeGoals' => $activeGoals,
            'upcomingClasses' => $upcomingClasses,
            'upcomingClasses' => $upcomingClasses,
            'notifications' => $notificationsArray,
            'latestAssessment' => $latestAssessment
        ]);
    }

    private function calculateWorkoutStreak($studentId)
    {
        // Calculate consecutive weeks with at least 1 class or workout (optimized)
        $currentWeek = now()->startOfWeek();
        $weeksBack = 52; // Max 1 year streak
        
        // Get all weeks with activity in optimized queries
        $classBookings = ClassBooking::where('student_id', $studentId)
            ->where('created_at', '>=', $currentWeek->copy()->subWeeks($weeksBack))
            ->selectRaw('YEARWEEK(created_at, 1) as week_year')
            ->distinct()
            ->pluck('week_year')
            ->toArray();
        
        $workoutLogs = \App\Models\WorkoutLog::where('student_id', $studentId)
            ->where('date', '>=', $currentWeek->copy()->subWeeks($weeksBack)->toDateString())
            ->selectRaw('YEARWEEK(date, 1) as week_year')
            ->distinct()
            ->pluck('week_year')
            ->toArray();
        
        // Combine and get unique weeks
        $activeWeeks = array_unique(array_merge($classBookings, $workoutLogs));
        
        // Calculate consecutive weeks from current week backwards
        $streakWeeks = 0;
        $checkWeek = $currentWeek->copy();
        
        for ($i = 0; $i < $weeksBack; $i++) {
            // Use YEARWEEK format to match database query (format: YYYYWW)
            $weekYear = (int) $checkWeek->format('YW'); // Year + ISO week number
            
            if (in_array($weekYear, $activeWeeks)) {
                $streakWeeks++;
                $checkWeek->subWeek();
            } else {
                break;
            }
        }
        
        // Return streak in days (streak weeks * 7)
        return $streakWeeks * 7;
    }


    public function studentProfile()
    {
        $studentId = Auth::guard('student')->id();
        $student = Auth::guard('student')->user();

        // Estatísticas pessoais detalhadas
        $yearlyClasses = ClassBooking::where('student_id', $studentId)->whereYear('created_at', now()->year)->count();
        $streakDays = $this->calculateWorkoutStreak($studentId);
        
        // Check and award achievements
        $this->achievementService->checkAndAwardAchievements($studentId);
        $studentAchievements = $this->achievementService->getStudentAchievements($studentId);
        
        $stats = [
            'totalClasses' => ClassBooking::where('student_id', $studentId)->count(),
            'monthlyClasses' => ClassBooking::where('student_id', $studentId)->whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count(),
            'weeklyClasses' => ClassBooking::where('student_id', $studentId)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'totalWorkouts' => Workout::where('student_id', $studentId)->count(),
            'completedExercises' => \App\Models\WorkoutLog::whereHas('workout', function($q) use($studentId) { $q->where('student_id', $studentId); })->count(),
            'streakDays' => $streakDays,
            'achievements' => $studentAchievements->count(),
            'achievementsList' => $studentAchievements,
        ];

        // Dados do contrato atual
        $currentContract = \App\Models\StudentContracts::where('student_id', $studentId)
            ->where('establishment_id', Session::get('establishment_id'))
            ->where('active', true)
            ->orderBy('end_date', 'desc')
            ->first();

        // Próximos pagamentos
        $upcomingPayments = collect([]);
        if ($currentContract) {
            $nextPaymentDate = \Carbon\Carbon::createFromFormat('Y-m-d', $currentContract->payment_date);
            if ($nextPaymentDate->isFuture() || $nextPaymentDate->isToday()) {
                $upcomingPayments = collect([
                    [
                        'date' => $currentContract->payment_date,
                        'amount' => $currentContract->amount,
                        'type' => 'Mensalidade',
                        'status' => 'Pendente',
                        'formatted_date' => $nextPaymentDate->locale('pt_BR')->isoFormat('DD [de] MMMM [de] YYYY'),
                    ]
                ]);
            }
        }

        // Avaliações físicas do estudante
        $recentAssessments = \App\Models\PhysicalAssessment::where('student_id', $studentId)
            ->orderBy('assessment_date', 'desc')
            ->limit(3)
            ->get();

        // Próximas aulas
        $upcomingClasses = ClassBooking::with(['classSchedule.modality'])
            ->where('student_id', $studentId)
            ->whereHas('classSchedule', function($q) {
                $q->whereDate('class_date', '>=', now()->toDateString());
            })
            ->orderBy('class_schedules.class_date', 'ASC')
            ->join('class_schedules', 'class_bookings.class_schedule_id', '=', 'class_schedules.id')
            ->select('class_bookings.*')
            ->limit(5)
            ->get();

        return view('student.profile', compact('student', 'stats', 'currentContract', 'upcomingPayments', 'recentAssessments', 'upcomingClasses'));
    }

    public function userProfile()
    {
        $userId = Auth::guard('user')->id();
        $user = Auth::guard('user')->user();
        $role = $user->getRoleForEstablishment(Session::get('establishment_id'));

        // Informações básicas do usuário admin/gestor
        $basicInfo = [
            'fullName' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? 'Não informado',
            'joined' => \Carbon\Carbon::parse($user->created_at)->locale('pt_BR')->isoFormat('DD/MM/YYYY'),
            'lastAccess' => \Carbon\Carbon::parse($user->last_login_at ?? $user->created_at)->locale('pt_BR')->isoFormat('DD/MM/YYYY [às] HH:mm'),
        ];

        // Informações do estabelecimento que o usuário administra
        $establishment = Establishment::with(['contracts' => function($query) {
            $query->where('active', true)->latest();
        }])->find(Session::get('establishment_id'));

        $establishmentInfo = null;
        if ($establishment) {
            $establishmentInfo = [
                'name' => $establishment->name,
                'address' => $establishment->address,
                'phone' => $establishment->phone ?? 'Não informado',
                'email' => $establishment->email ?? 'Não informado',
                'studentsCount' => $establishment->students()->where('active', 1)->count(),
                'activeContracts' => $establishment->contracts->count(),
                'monthlyRevenue' => $establishment->contracts->where('created_at', '>=', now()->startOfMonth())->sum('amount'),
            ];
        }

        // Estatísticas de gerenciamento (se for superuser)
        $managementStats = null;
        if ($role && in_array($role->name, ['superuser'])) {
            $allEstablishments = Establishment::all();
            $managementStats = [
                'totalEstablishments' => $allEstablishments->count(),
                'activeEstablishments' => $allEstablishments->where('active', 1)->count(),
                'totalStudents' => $allEstablishments->sum(function($est) {
                    return $est->students()->where('active', 1)->count();
                }),
                'totalRevenue' => EstablishmentContracts::where('active', true)->sum('amount'),
                'monthlyRevenue' => EstablishmentContracts::where('active', true)
                    ->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month)
                    ->sum('amount'),
            ];
        }

        // Próximas tarefas/revisões (pendências administrativas)
        $pendingTasks = [];
        if ($role && in_array($role->name, ['superuser'])) {
            $pendingTasks = [
                'expiringContracts' => EstablishmentContracts::where('active', true)
                    ->where('end_date', '<=', now()->addDays(30))
                    ->count(),
                'inactiveStudents' => Student::where('active', 0)->count(),
                'newStudentsThisMonth' => Student::whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month)
                    ->count(),
            ];
        } elseif ($establishment) {
            $pendingTasks = [
                'expiringContracts' => EstablishmentContracts::where('establishment_id', Session::get('establishment_id'))
                    ->where('active', true)
                    ->where('end_date', '<=', now()->addDays(30))
                    ->count(),
                'newStudentsThisMonth' => Student::whereHas('establishment', function($q) {
                    $q->where('establishment_id', Session::get('establishment_id'));
                })
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count(),
            ];
        }

        return view('admin.profile', compact('user', 'basicInfo', 'establishmentInfo', 'managementStats', 'pendingTasks', 'role'));
    }

    public function updateStudentProfile(Request $request)
    {
        $studentId = Auth::guard('student')->id();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $studentId,
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date|before:today',
            'height' => 'nullable|numeric|min:0.5|max:3.0',
            'weight' => 'nullable|numeric|min:10|max:500',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:10',
        ]);

        try {
            $student = \App\Models\Student::findOrFail($studentId);

            // Atualizar apenas os campos permitidos
            $student->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'birth_date' => $validatedData['birth_date'],
                'height' => $validatedData['height'],
                'weight' => $validatedData['weight'],
                'address' => $validatedData['address'],
                'city' => $validatedData['city'],
                'state' => $validatedData['state'],
                'zip_code' => $validatedData['zip_code'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Perfil atualizado com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar perfil: ' . $e->getMessage()
            ], 500);
        }
    }

}
