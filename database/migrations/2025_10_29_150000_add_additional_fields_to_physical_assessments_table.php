<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFieldsToPhysicalAssessmentsTable extends Migration
{
    public function up()
    {
        Schema::table('physical_assessments', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('student_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->decimal('chest_measurement', 6, 2)->nullable()->after('muscle_mass_percentage');
            $table->decimal('waist_measurement', 6, 2)->nullable()->after('chest_measurement');
            $table->decimal('hip_measurement', 6, 2)->nullable()->after('waist_measurement');
            $table->decimal('arm_measurement', 6, 2)->nullable()->after('hip_measurement');
            $table->decimal('thigh_measurement', 6, 2)->nullable()->after('arm_measurement');

            $table->integer('resting_heart_rate')->nullable()->after('thigh_measurement');
            $table->integer('max_heart_rate')->nullable()->after('resting_heart_rate');
            $table->decimal('blood_pressure_systolic', 5, 1)->nullable()->after('max_heart_rate');
            $table->decimal('blood_pressure_diastolic', 5, 1)->nullable()->after('blood_pressure_systolic');

            $table->text('observations')->nullable()->after('blood_pressure_diastolic');
            $table->text('goals')->nullable()->after('observations');
            $table->text('recommendations')->nullable()->after('goals');
        });
    }

    public function down()
    {
        Schema::table('physical_assessments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'chest_measurement',
                'waist_measurement',
                'hip_measurement',
                'arm_measurement',
                'thigh_measurement',
                'resting_heart_rate',
                'max_heart_rate',
                'blood_pressure_systolic',
                'blood_pressure_diastolic',
                'observations',
                'goals',
                'recommendations',
            ]);
        });
    }
}
