<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('class_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->unsignedBigInteger('class_schedule_id');
            $table->foreign('class_schedule_id')->references('id')->on('class_schedules')->onDelete('cascade');
            $table->dateTime('booking_date');
            $table->boolean('checked_in')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_bookings');
    }
}