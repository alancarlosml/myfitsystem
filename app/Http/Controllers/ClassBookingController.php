<?php

namespace App\Http\Controllers;

use App\Models\ClassBooking;
use Illuminate\Http\Request;

class ClassBookingController extends Controller
{
    public function index()
    {
        $class_bookings = ClassBooking::select('class_bookings.*')
                                        ->leftJoin('students', 'class_bookings.establishment_id', '=', 'establishments.id')
                                        ->leftJoin('modalities', 'class_bookings.modality_id', '=', 'modalities.id')
                                        ->orderBy('class_bookings.class_date','desc')
                                        ->orderBy('class_bookings.start_time','desc')
                                        ->with(['modality', 'establishment'])->get();

        return view('admin.class_bookings.index', ['class_bookings' => $class_bookings]);
    }
}
