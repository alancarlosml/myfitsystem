<?php

namespace App\Http\Controllers;

use App\Models\ClassBooking;
use App\Models\ClassSchedule;
use App\Services\AchievementService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ClassBookingController extends Controller
{
    protected $guard;
    protected $achievementService;

    public function __construct(AchievementService $achievementService)
    {
        $this->guard = Auth::guard('user')->check() ? 'user' : 'student';
        $this->achievementService = $achievementService;
    }
    public function index(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        Carbon::setLocale('pt_BR');

        $date = Carbon::createFromDate($year, $month, 1);

        $monthName = $date->translatedFormat('F');

        $monthName = $date->isoFormat('MMMM');

        $query = ClassSchedule::whereDate('class_date', '>=', Carbon::now('America/Sao_Paulo')->toDateString())
                                ->orderBy('class_date');

        // For students, filter by establishment and add booking information
        if ($this->guard === 'student') {
            $establishmentId = Session::get('establishment_id');
            $studentId = Auth::guard('student')->id();

            $query->where('establishment_id', $establishmentId);

            // Note: We'll fetch bookings separately to ensure accuracy

            // Get real booking stats for student
            $bookingsStatistics = $this->getBookingsStatistics($studentId);
        }

        $classSchedules = $query->get();
        
        // For students, ensure booking_id is properly set
        if ($this->guard === 'student' && $classSchedules->isNotEmpty()) {
            $scheduleIds = $classSchedules->pluck('id');
            $studentId = Auth::guard('student')->id();
            
            // Get all bookings for these schedules
            $bookings = ClassBooking::whereIn('class_schedule_id', $scheduleIds)
                ->where('student_id', $studentId)
                ->whereNull('deleted_at')
                ->pluck('id', 'class_schedule_id');
            
            // Map booking_id to each schedule
            $classSchedules->each(function($schedule) use ($bookings) {
                $schedule->booking_id = $bookings->get($schedule->id) ?? null;
            });
        }

        if ($this->guard === 'student') {
            $studentBookings = $this->getStudentBookings($studentId, 5); // Get next 5 bookings
        }

        $startOfMonth = Carbon::createFromDate($year, $month, 1);
        $firstDayOfWeek = $startOfMonth->dayOfWeek;
        $daysInMonth = $startOfMonth->daysInMonth;
        $daysFromPrevMonth = $firstDayOfWeek ? $firstDayOfWeek : 7;
        $startDate = $startOfMonth->copy()->subDays($daysFromPrevMonth);

        $calendar = [];
        $today = Carbon::today('America/Sao_Paulo')->format('Y-m-d');

        for ($i = 0; $i < 35; $i++) {
            $date = $startDate->copy()->addDays($i);
            $formattedDate = $date->format('Y-m-d');
            if (!isset($calendar[$formattedDate])) {
                $calendar[$formattedDate] = [
                    'date' => $date,
                    'events' => [],
                    'has_booked_event' => false,
                    'is_today' => $formattedDate === $today,
                ];
            }

            foreach ($classSchedules as $schedule) {
                $eventDate = Carbon::parse($schedule->class_date)->format('Y-m-d');
                if ($formattedDate == $eventDate) {
                    $calendar[$formattedDate]['events'][] = $schedule;
                    // For students, check if they have booked this class
                    if ($this->guard === 'student' && isset($schedule->booking_id) && $schedule->booking_id) {
                        $calendar[$formattedDate]['has_booked_event'] = true;
                    }
                }
            }
        }

        if ($request->ajax()) {
            // Return just the calendar content for AJAX requests
            return view('student.class_bookings.partials.calendar', compact('calendar', 'year', 'month', 'monthName'));
        }

        $view = $this->guard === 'student' ? 'student.class_bookings.index' : 'admin.class_bookings.index';

        if ($this->guard === 'student') {
            return view($view, compact('calendar', 'year', 'month', 'monthName', 'bookingsStatistics', 'studentBookings'));
        } else {
            return view($view, compact('calendar', 'year', 'month', 'monthName'));
        }
    }

    public function getEvents(Request $request)
    {
        $date = $request->input('date');
        $establishmentId = Session::get('establishment_id');

        $query = ClassSchedule::whereDate('class_date', $date)
            ->where('establishment_id', $establishmentId)
            ->leftJoin('users', 'class_schedules.user_id', '=', 'users.id')
            ->select('class_schedules.id', 'start_time', 'end_time', 'description', 'users.name as user_name', 'class_schedules.modality_id');

        $events = $query->get();

        // For students, add booking information
        if ($this->guard === 'student') {
            $studentId = Auth::guard('student')->id();
            $scheduleIds = $events->pluck('id');
            
            // Get all bookings for these schedules
            $bookings = ClassBooking::whereIn('class_schedule_id', $scheduleIds)
                ->where('student_id', $studentId)
                ->whereNull('deleted_at')
                ->pluck('id', 'class_schedule_id');
            
            // Map booking_id to each event
            $events->each(function($event) use ($bookings) {
                $event->booking_id = $bookings->get($event->id) ?? null;
            });
        }

        return response()->json(['events' => $events]);
    }

    public function book(Request $request)
    {
        $request->validate([
            'class_schedule_id' => 'required|exists:class_schedules,id',
        ]);

        $studentId = Auth::guard('student')->id();
        $classScheduleId = $request->class_schedule_id;

        // Check if already booked
        $existingBooking = ClassBooking::where('student_id', $studentId)
                                       ->where('class_schedule_id', $classScheduleId)
                                       ->first();

        if ($existingBooking) {
            return response()->json(['success' => false, 'message' => 'Você já está inscrito nesta aula.']);
        }

        // Check class capacity or other constraints if needed

        $booking = ClassBooking::create([
            'student_id' => $studentId,
            'class_schedule_id' => $classScheduleId,
            'booking_date' => now(),
        ]);

        // Check and award achievements
        $this->achievementService->checkAndAwardAchievements($studentId);

        return response()->json(['success' => true, 'message' => 'Aula reservada com sucesso!', 'booking_id' => $booking->id]);
    }

    public function cancelBooking($bookingId)
    {
        $studentId = Auth::guard('student')->id();

        // Find the booking and verify ownership
        $booking = ClassBooking::where('id', $bookingId)
                               ->where('student_id', $studentId)
                               ->firstOrFail();

        // Check if the class starts in less than 2 hours to prevent last-minute cancellations
        $now = now();
        $classTime = $booking->classSchedule->class_date->copy()->setTimeFrom(
            $booking->classSchedule->start_time
        );

        if ($classTime->diffInHours($now) <= 2 && $classTime->isFuture()) {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível cancelar reservas com menos de 2 horas de antecedência.'
            ], 400);
        }

        // Delete the booking
        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reserva cancelada com sucesso!'
        ]);
    }

    private function getBookingsStatistics($studentId)
    {
        // Count classes this month
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $classesThisMonth = ClassBooking::where('student_id', $studentId)
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->count();

        // Count hours this week
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();
        $weekHours = ClassBooking::where('student_id', $studentId)
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->with(['classSchedule'])
            ->get()
            ->sum(function($booking) {
                $start = \Carbon\Carbon::parse($booking->classSchedule->start_time);
                $end = \Carbon\Carbon::parse($booking->classSchedule->end_time);
                return $end->diffInHours($start);
            });

        // Next class
        $nextClass = ClassBooking::where('student_id', $studentId)
            ->with(['classSchedule'])
            ->whereHas('classSchedule', function($q) {
                $q->whereDate('class_date', '>=', now()->toDateString());
            })
            ->orderBy('class_schedules.class_date', 'ASC')
            ->orderBy('class_schedules.start_time', 'ASC')
            ->join('class_schedules', 'class_bookings.class_schedule_id', '=', 'class_schedules.id')
            ->select('class_bookings.*')
            ->first();

        // Days until next class
        $daysUntil = null;
        if ($nextClass) {
            $today = now()->startOfDay();
            $classDate = \Carbon\Carbon::parse($nextClass->classSchedule->class_date)->startOfDay();
            $daysUntil = $classDate->diffInDays($today);
        }

        return [
            'classesThisMonth' => $classesThisMonth,
            'weekHours' => $weekHours,
            'nextClass' => $nextClass,
            'daysUntil' => $daysUntil
        ];
    }

    private function getStudentBookings($studentId, $limit = 5)
    {
        return ClassBooking::where('student_id', $studentId)
            ->with(['classSchedule.modality'])
            ->whereHas('classSchedule', function($q) {
                $q->whereDate('class_date', '>=', now()->toDateString());
            })
            ->orderBy('class_schedules.class_date', 'ASC')
            ->orderBy('class_schedules.start_time', 'ASC')
            ->join('class_schedules', 'class_bookings.class_schedule_id', '=', 'class_schedules.id')
            ->select('class_bookings.*', 'class_schedules.class_date', 'class_schedules.start_time', 'class_schedules.end_time', 'class_schedules.modality_id')
            ->limit($limit)
            ->get();
    }
}
