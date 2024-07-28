<?php

namespace App\Http\Controllers;

use App\Models\ClassBooking;
use App\Models\ClassSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClassBookingController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        Carbon::setLocale('pt_BR');

        $date = Carbon::createFromDate($year, $month, 1);

        $monthName = $date->translatedFormat('F');

        $monthName = $date->isoFormat('MMMM');

        $classSchedules = ClassSchedule::whereDate('class_date', '>=', Carbon::now('America/Sao_Paulo')->toDateString())
                                ->orderBy('class_date')
                                ->get();

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
                    'is_today' => $formattedDate === $today,
                ];
            }

            foreach ($classSchedules as $schedule) {
                $eventDate = Carbon::parse($schedule->class_date)->format('Y-m-d');
                if ($formattedDate == $eventDate) {
                    $calendar[$formattedDate]['events'][] = $schedule;
                }
            }
        }

        return view('admin.class_bookings.index', compact('calendar', 'year', 'month', 'monthName'));
    }

    public function getEvents(Request $request)
    {
        $date = $request->input('date');

        // Obtenha os eventos com join na tabela class_schedules
        // $events = ClassBooking::whereDate('class_schedules.class_dates', $date)
        //     ->join('class_schedules', 'class_bookings.class_schedule_id', '=', 'class_schedules.id')
        //     ->select('class_schedules.start_time', 'class_schedules.end_time', 'class_schedules.description')
        //     ->get();

        $events = ClassSchedule::whereDate('class_date', $date)
            ->join('users', 'class_schedules.user_id', '=', 'users.id')
            ->select('start_time', 'end_time', 'description', 'users.name as user_name')
            ->get();

        return response()->json(['events' => $events]);
    }
}
