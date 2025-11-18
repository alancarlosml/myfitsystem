<?php

namespace App\Services;

use App\Models\Establishment;
use App\Models\Student;
use App\Models\EstablishmentContracts;
use App\Models\StudentContracts;
use App\Models\ClassSchedule;
use App\Models\ClassBooking;
use App\Models\Modality;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardService
{
    /**
     * Get dashboard data for superuser - SaaS Business Metrics
     */
    public function getSuperuserDashboardData($currentMonth, $currentYear)
    {
        $data = [];

        // Basic establishment info
        $data['establishments_active'] = Establishment::where('active', 1)->count();
        $data['establishments_inactive'] = Establishment::where('active', 0)->count();
        $data['total_establishments'] = Establishment::count();

        // MRR - Monthly Recurring Revenue (active contracts this month)
        $data['mrr'] = EstablishmentContracts::where('active', true)
            ->where('status', 'pago')
            ->whereNotNull('paid_at')
            ->whereYear('paid_at', $currentYear)
            ->whereMonth('paid_at', $currentMonth)
            ->sum('amount');

        // ARR - Annual Recurring Revenue (estimated from active contracts)
        $data['arr'] = EstablishmentContracts::where('active', true)
            ->where('status', 'pago')
            ->whereNotNull('paid_at')
            ->whereYear('paid_at', $currentYear)
            ->sum('amount');

        // Revenue metrics - using status for better accuracy
        $data['total_mes'] = EstablishmentContracts::where('status', 'pago')
            ->whereNotNull('paid_at')
            ->whereYear('paid_at', $currentYear)
            ->whereMonth('paid_at', $currentMonth)
            ->sum('amount');
        
        $data['total_ano'] = EstablishmentContracts::where('status', 'pago')
            ->whereNotNull('paid_at')
            ->whereYear('paid_at', $currentYear)
            ->sum('amount');
        
        // Amounts to receive (pending payments)
        $data['a_receber_mes'] = EstablishmentContracts::where('status', 'pendente')
            ->whereYear('payment_date', $currentYear)
            ->whereMonth('payment_date', $currentMonth)
            ->sum('amount');
        
        $data['a_receber_total'] = EstablishmentContracts::where('status', 'pendente')
            ->sum('amount');
        
        // Overdue payments
        $data['vencidos'] = EstablishmentContracts::where('status', 'vencido')
            ->sum('amount');

        // Contracts expiring soon (next 30 days) - CRITICAL for SaaS
        $data['contracts_expiring_soon'] = EstablishmentContracts::where('active', true)
            ->whereBetween('end_date', [now(), now()->addDays(30)])
            ->with('establishment')
            ->orderBy('end_date', 'asc')
            ->get();
        
        $data['contracts_expiring_count'] = $data['contracts_expiring_soon']->count();
        $data['contracts_expiring_value'] = $data['contracts_expiring_soon']->sum('amount');

        // Contracts expired but still active (need renewal)
        $data['contracts_expired'] = EstablishmentContracts::where('active', true)
            ->where('end_date', '<', now())
            ->with('establishment')
            ->orderBy('end_date', 'asc')
            ->get();
        
        $data['contracts_expired_count'] = $data['contracts_expired']->count();
        $data['contracts_expired_value'] = $data['contracts_expired']->sum('amount');

        // Growth metrics
        $data = array_merge($data, $this->calculateRevenueGrowth(null, $currentMonth, $currentYear, true));

        // Establishments with payment issues
        $data['establishments_with_issues'] = Establishment::whereHas('contracts', function($query) {
            $query->where('active', true)
                  ->whereIn('status', ['pendente', 'vencido']);
        })
        ->with(['contracts' => function($query) {
            $query->where('active', true)
                  ->whereIn('status', ['pendente', 'vencido'])
                  ->orderBy('payment_date', 'asc');
        }])
        ->get();

        // Top performing establishments (revenue)
        $data['top_establishments'] = Establishment::withCount([
            'contracts as total_revenue' => function ($query) use ($currentYear) {
                $query->where('status', 'pago')
                      ->whereNotNull('paid_at')
                      ->whereYear('paid_at', $currentYear)
                      ->select(DB::raw('sum(amount)'));
            }
        ])
        ->orderByDesc('total_revenue')
        ->take(5)
        ->get();

        // New establishments this month
        $data['new_establishments_month'] = Establishment::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->count();

        // Churn rate calculation (establishments that stopped paying)
        $lastMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
        $lastMonthYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;
        
        $activeLastMonth = EstablishmentContracts::where('active', true)
            ->where('status', 'pago')
            ->whereNotNull('paid_at')
            ->whereYear('paid_at', $lastMonthYear)
            ->whereMonth('paid_at', $lastMonth)
            ->distinct('establishment_id')
            ->count('establishment_id');
        
        $activeThisMonth = EstablishmentContracts::where('active', true)
            ->where('status', 'pago')
            ->whereNotNull('paid_at')
            ->whereYear('paid_at', $currentYear)
            ->whereMonth('paid_at', $currentMonth)
            ->distinct('establishment_id')
            ->count('establishment_id');
        
        $data['churn_rate'] = $activeLastMonth > 0 
            ? (($activeLastMonth - $activeThisMonth) / $activeLastMonth) * 100 
            : 0;

        // Contracts with pending payments
        $data['contracts_pending_payment'] = EstablishmentContracts::where('status', 'pendente')
            ->where('active', true)
            ->count();
        
        // Contracts with overdue payments
        $data['contracts_overdue'] = EstablishmentContracts::where('status', 'vencido')
            ->where('active', true)
            ->count();

        // Average revenue per establishment
        $data['avg_revenue_per_establishment'] = $data['establishments_active'] > 0
            ? $data['total_mes'] / $data['establishments_active']
            : 0;

        return $data;
    }

    /**
     * Get dashboard data for admin/establishment
     */
    public function getEstablishmentDashboardData($establishmentId, $currentMonth, $currentYear)
    {
        $data = [];
        $establishment = Establishment::find($establishmentId);
        $data['current_establishment'] = $establishment;

        // Student metrics for this establishment (optimized - single query)
        $studentsStats = DB::table('students')
            ->join('student_establishment', 'students.id', '=', 'student_establishment.student_id')
            ->where('student_establishment.establishment_id', $establishmentId)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN students.active = 1 THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN students.active = 0 THEN 1 ELSE 0 END) as inactive
            ')
            ->first();

        $data['total_students'] = $studentsStats->total ?? 0;
        $data['students_active'] = $studentsStats->active ?? 0;
        $data['students_inactive'] = $studentsStats->inactive ?? 0;

        // Revenue metrics for this establishment - using status
        $data['total_mes'] = EstablishmentContracts::where('establishment_id', $establishmentId)
            ->where('status', 'pago')
            ->whereNotNull('paid_at')
            ->whereYear('paid_at', $currentYear)
            ->whereMonth('paid_at', $currentMonth)
            ->sum('amount');
        
        $data['total_ano'] = EstablishmentContracts::where('establishment_id', $establishmentId)
            ->where('status', 'pago')
            ->whereNotNull('paid_at')
            ->whereYear('paid_at', $currentYear)
            ->sum('amount');
        
        // Student contracts revenue (received)
        $data['recebido_alunos_mes'] = StudentContracts::where('establishment_id', $establishmentId)
            ->where('status', 'pago')
            ->whereNotNull('paid_at')
            ->whereYear('paid_at', $currentYear)
            ->whereMonth('paid_at', $currentMonth)
            ->sum('amount');
        
        $data['recebido_alunos_ano'] = StudentContracts::where('establishment_id', $establishmentId)
            ->where('status', 'pago')
            ->whereNotNull('paid_at')
            ->whereYear('paid_at', $currentYear)
            ->sum('amount');
        
        // Amounts to receive from students
        $data['a_receber_alunos'] = StudentContracts::where('establishment_id', $establishmentId)
            ->where('status', 'pendente')
            ->sum('amount');
        
        // Overdue student payments
        $data['vencidos_alunos'] = StudentContracts::where('establishment_id', $establishmentId)
            ->where('status', 'vencido')
            ->sum('amount');

        // Growth metrics
        $data = array_merge($data, $this->calculateRevenueGrowth($establishmentId, $currentMonth, $currentYear, false));

        // Popular modalities in this establishment
        $data['popular_modalities'] = Modality::join('class_schedules', 'modalities.id', '=', 'class_schedules.modality_id')
            ->where('class_schedules.establishment_id', $establishmentId)
            ->groupBy('modalities.id', 'modalities.name')
            ->select('modalities.id', 'modalities.name', DB::raw('count(*) as schedule_count'))
            ->orderByDesc('schedule_count')
            ->take(5)
            ->get();

        // Class attendance this month (optimized - direct join)
        $data['class_bookings_this_month'] = ClassBooking::join('class_schedules', 'class_bookings.class_schedule_id', '=', 'class_schedules.id')
            ->where('class_schedules.establishment_id', $establishmentId)
            ->whereYear('class_bookings.created_at', $currentYear)
            ->whereMonth('class_bookings.created_at', $currentMonth)
            ->count();

        // Students enrolled this month
        $data['enrolled_students_this_month'] = StudentContracts::where('establishment_id', $establishmentId)
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->where('active', true)
            ->count();

        // Pending payments for this establishment
        $data['pending_payments'] = EstablishmentContracts::where('establishment_id', $establishmentId)
            ->where('active', true)
            ->where('end_date', '<', now()->addDays(30))
            ->where('end_date', '>', now())
            ->count();
        
        // Student contracts with pending payments
        $data['students_pending_payment'] = StudentContracts::where('establishment_id', $establishmentId)
            ->where('status', 'pendente')
            ->where('active', true)
            ->count();
        
        // Student contracts overdue
        $data['students_overdue'] = StudentContracts::where('establishment_id', $establishmentId)
            ->where('status', 'vencido')
            ->where('active', true)
            ->count();

        // Staff count (optimized - direct join)
        $data['total_staff'] = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->where('role_user.establishment_id', $establishmentId)
            ->whereNull('users.deleted_at')
            ->distinct()
            ->count();

        // Today's classes
        $data['classes_today'] = ClassSchedule::where('establishment_id', $establishmentId)
            ->whereDate('class_date', now()->toDateString())
            ->count();

        return $data;
    }

    /**
     * Calculate revenue growth metrics
     */
    private function calculateRevenueGrowth($establishmentId, $currentMonth, $currentYear, $isSuperuser)
    {
        $lastMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
        $lastMonthYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;

        $data = [];

        if ($isSuperuser) {
            $data['revenue_last_month'] = EstablishmentContracts::where('status', 'pago')
                ->whereNotNull('paid_at')
                ->whereYear('paid_at', $lastMonthYear)
                ->whereMonth('paid_at', $lastMonth)
                ->sum('amount');
            $currentMonthRevenue = EstablishmentContracts::where('status', 'pago')
                ->whereNotNull('paid_at')
                ->whereYear('paid_at', $currentYear)
                ->whereMonth('paid_at', $currentMonth)
                ->sum('amount');
        } else {
            $data['revenue_last_month'] = EstablishmentContracts::where('establishment_id', $establishmentId)
                ->where('status', 'pago')
                ->whereNotNull('paid_at')
                ->whereYear('paid_at', $lastMonthYear)
                ->whereMonth('paid_at', $lastMonth)
                ->sum('amount');
            
            // Also include student contracts in revenue for growth calculation
            $data['revenue_last_month'] += StudentContracts::where('establishment_id', $establishmentId)
                ->where('status', 'pago')
                ->whereNotNull('paid_at')
                ->whereYear('paid_at', $lastMonthYear)
                ->whereMonth('paid_at', $lastMonth)
                ->sum('amount');
            
            $currentMonthRevenue = EstablishmentContracts::where('establishment_id', $establishmentId)
                ->where('status', 'pago')
                ->whereNotNull('paid_at')
                ->whereYear('paid_at', $currentYear)
                ->whereMonth('paid_at', $currentMonth)
                ->sum('amount');
            
            $currentMonthRevenue += StudentContracts::where('establishment_id', $establishmentId)
                ->where('status', 'pago')
                ->whereNotNull('paid_at')
                ->whereYear('paid_at', $currentYear)
                ->whereMonth('paid_at', $currentMonth)
                ->sum('amount');
        }

        $data['revenue_growth'] = $data['revenue_last_month'] > 0
            ? (($currentMonthRevenue - $data['revenue_last_month']) / $data['revenue_last_month']) * 100
            : ($currentMonthRevenue > 0 ? 100 : 0);

        return $data;
    }

    /**
     * Get monthly revenues for chart (last 6 months) - optimized with single query
     */
    public function getMonthlyRevenues($establishmentId = null, $isSuperuser = false)
    {
        $startDate = now()->subMonths(5)->startOfMonth();
        $endDate = now()->endOfMonth();
        
        // Single query for establishment contracts
        $establishmentQuery = EstablishmentContracts::where('status', 'pago')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$startDate, $endDate]);
        
        if (!$isSuperuser && $establishmentId) {
            $establishmentQuery->where('establishment_id', $establishmentId);
        }
        
        $establishmentRevenues = $establishmentQuery
            ->selectRaw('YEAR(paid_at) as year, MONTH(paid_at) as month, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(function($item) {
                return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            });
        
        // Single query for student contracts (if not superuser)
        $studentRevenues = collect([]);
        if (!$isSuperuser && $establishmentId) {
            $studentRevenues = StudentContracts::where('establishment_id', $establishmentId)
                ->where('status', 'pago')
                ->whereNotNull('paid_at')
                ->whereBetween('paid_at', [$startDate, $endDate])
                ->selectRaw('YEAR(paid_at) as year, MONTH(paid_at) as month, SUM(amount) as total')
                ->groupBy('year', 'month')
                ->get()
                ->keyBy(function($item) {
                    return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
                });
        }
        
        // Build monthly revenues array
        $monthlyRevenues = [];
        $monthLabels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;
            $key = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
            
            $sum = $establishmentRevenues->get($key)->total ?? 0;
            $sum += $studentRevenues->get($key)->total ?? 0;
            
            $monthlyRevenues[] = $sum;
            $monthLabels[] = $date->format('M Y');
        }

        return [
            'monthlyRevenues' => $monthlyRevenues,
            'monthLabels' => $monthLabels,
        ];
    }
}

