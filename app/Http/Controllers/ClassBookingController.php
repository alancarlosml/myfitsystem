<?php

namespace App\Http\Controllers;

use App\Models\ClassBooking;
use App\Models\ClassSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClassBookingController extends Controller
{
    public function index()
    {
        #$currentMonth = Carbon::now()->month;
        $classSchedules = ClassSchedule::orderBy('class_date')
                                        ->get();

        return view('admin.class_bookings.index', ['classSchedules' => $classSchedules]);
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
            ->select('start_time', 'end_time', 'description')
            ->get();

        return response()->json(['events' => $events]);
    }
}
