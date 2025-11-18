<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Indexes for establishment_contracts
        Schema::table('establishment_contracts', function (Blueprint $table) {
            $table->index(['establishment_id', 'status', 'paid_at'], 'idx_est_contracts_est_status_paid');
            $table->index(['status', 'paid_at', 'active'], 'idx_est_contracts_status_paid_active');
            $table->index(['establishment_id', 'active', 'end_date'], 'idx_est_contracts_est_active_end');
            $table->index(['payment_date'], 'idx_est_contracts_payment_date');
        });

        // Indexes for student_contracts
        Schema::table('student_contracts', function (Blueprint $table) {
            $table->index(['student_id', 'establishment_id', 'status'], 'idx_stu_contracts_stu_est_status');
            $table->index(['establishment_id', 'status', 'paid_at'], 'idx_stu_contracts_est_status_paid');
            $table->index(['status', 'paid_at', 'active'], 'idx_stu_contracts_status_paid_active');
            $table->index(['establishment_id', 'active', 'created_at'], 'idx_stu_contracts_est_active_created');
            $table->index(['payment_date'], 'idx_stu_contracts_payment_date');
        });

        // Indexes for class_bookings
        Schema::table('class_bookings', function (Blueprint $table) {
            $table->index(['student_id', 'created_at'], 'idx_bookings_student_created');
            $table->index(['class_schedule_id', 'student_id'], 'idx_bookings_schedule_student');
            $table->index(['created_at'], 'idx_bookings_created_at');
            $table->index(['checked_in'], 'idx_bookings_checked_in');
        });

        // Indexes for class_schedules
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->index(['establishment_id', 'class_date'], 'idx_schedules_est_date');
            $table->index(['class_date', 'start_time'], 'idx_schedules_date_time');
            $table->index(['modality_id'], 'idx_schedules_modality');
        });

        // Indexes for workout_logs
        Schema::table('workout_logs', function (Blueprint $table) {
            $table->index(['student_id', 'date'], 'idx_workout_logs_student_date');
            $table->index(['establishment_id', 'student_id'], 'idx_workout_logs_est_student');
            $table->index(['date'], 'idx_workout_logs_date');
        });

        // Indexes for workouts
        Schema::table('workouts', function (Blueprint $table) {
            $table->index(['student_id', 'establishment_id'], 'idx_workouts_student_est');
            $table->index(['establishment_id'], 'idx_workouts_est');
        });

        // Indexes for students
        Schema::table('students', function (Blueprint $table) {
            $table->index(['active'], 'idx_students_active');
            $table->index(['created_at'], 'idx_students_created_at');
        });

        // Indexes for student_establishment pivot
        if (Schema::hasTable('student_establishment')) {
            Schema::table('student_establishment', function (Blueprint $table) {
                $table->index(['student_id', 'establishment_id'], 'idx_stu_est_pivot');
                $table->index(['establishment_id'], 'idx_stu_est_est_id');
            });
        }

        // Indexes for user_establishment pivot
        if (Schema::hasTable('user_establishment')) {
            Schema::table('user_establishment', function (Blueprint $table) {
                $table->index(['user_id', 'establishment_id'], 'idx_user_est_pivot');
                $table->index(['establishment_id'], 'idx_user_est_est_id');
            });
        }

        // Indexes for physical_assessments
        if (Schema::hasTable('physical_assessments')) {
            Schema::table('physical_assessments', function (Blueprint $table) {
                $table->index(['student_id', 'establishment_id'], 'idx_assessments_stu_est');
                $table->index(['assessment_date'], 'idx_assessments_date');
            });
        }

        // Indexes for exercises
        if (Schema::hasTable('exercises')) {
            Schema::table('exercises', function (Blueprint $table) {
                $table->index(['establishment_id', 'active'], 'idx_exercises_est_active');
                $table->index(['category_id'], 'idx_exercises_category');
            });
        }

        // Indexes for achievements
        if (Schema::hasTable('achievements')) {
            Schema::table('achievements', function (Blueprint $table) {
                $table->index(['active', 'type'], 'idx_achievements_active_type');
            });
        }

        // Indexes for achievement_student
        if (Schema::hasTable('achievement_student')) {
            Schema::table('achievement_student', function (Blueprint $table) {
                $table->index(['student_id', 'earned_at'], 'idx_ach_student_stu_earned');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('establishment_contracts', function (Blueprint $table) {
            $table->dropIndex('idx_est_contracts_est_status_paid');
            $table->dropIndex('idx_est_contracts_status_paid_active');
            $table->dropIndex('idx_est_contracts_est_active_end');
            $table->dropIndex('idx_est_contracts_payment_date');
        });

        Schema::table('student_contracts', function (Blueprint $table) {
            $table->dropIndex('idx_stu_contracts_stu_est_status');
            $table->dropIndex('idx_stu_contracts_est_status_paid');
            $table->dropIndex('idx_stu_contracts_status_paid_active');
            $table->dropIndex('idx_stu_contracts_est_active_created');
            $table->dropIndex('idx_stu_contracts_payment_date');
        });

        Schema::table('class_bookings', function (Blueprint $table) {
            $table->dropIndex('idx_bookings_student_created');
            $table->dropIndex('idx_bookings_schedule_student');
            $table->dropIndex('idx_bookings_created_at');
            $table->dropIndex('idx_bookings_checked_in');
        });

        Schema::table('class_schedules', function (Blueprint $table) {
            $table->dropIndex('idx_schedules_est_date');
            $table->dropIndex('idx_schedules_date_time');
            $table->dropIndex('idx_schedules_modality');
        });

        Schema::table('workout_logs', function (Blueprint $table) {
            $table->dropIndex('idx_workout_logs_student_date');
            $table->dropIndex('idx_workout_logs_est_student');
            $table->dropIndex('idx_workout_logs_date');
        });

        Schema::table('workouts', function (Blueprint $table) {
            $table->dropIndex('idx_workouts_student_est');
            $table->dropIndex('idx_workouts_est');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('idx_students_active');
            $table->dropIndex('idx_students_created_at');
        });

        if (Schema::hasTable('student_establishment')) {
            Schema::table('student_establishment', function (Blueprint $table) {
                $table->dropIndex('idx_stu_est_pivot');
                $table->dropIndex('idx_stu_est_est_id');
            });
        }

        if (Schema::hasTable('user_establishment')) {
            Schema::table('user_establishment', function (Blueprint $table) {
                $table->dropIndex('idx_user_est_pivot');
                $table->dropIndex('idx_user_est_est_id');
            });
        }
    }
};
