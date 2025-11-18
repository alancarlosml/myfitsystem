<?php

namespace App\Services;

use App\Models\StudentGoal;
use App\Models\Student;
use App\Models\ClassBooking;
use App\Models\WorkoutLog;
use App\Models\PhysicalAssessment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GoalService
{
    /**
     * Get or create default weekly classes goal for a student
     */
    public function getOrCreateWeeklyClassesGoal($studentId, $establishmentId = null, $defaultTarget = 4)
    {
        $goal = StudentGoal::where('student_id', $studentId)
            ->where('goal_type', 'weekly_classes')
            ->where('active', true)
            ->where(function($q) use ($establishmentId) {
                if ($establishmentId) {
                    $q->where('establishment_id', $establishmentId)->orWhereNull('establishment_id');
                } else {
                    $q->whereNull('establishment_id');
                }
            })
            ->whereDate('start_date', '<=', now()->toDateString())
            ->where(function($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now()->toDateString());
            })
            ->first();

        if (!$goal) {
            // Create default goal
            $goal = $this->createGoal([
                'student_id' => $studentId,
                'establishment_id' => $establishmentId,
                'goal_type' => 'weekly_classes',
                'goal_name' => 'Aulas Semanais',
                'target_value' => $defaultTarget,
                'unit' => 'aulas',
                'start_date' => now()->startOfWeek()->toDateString(),
                'end_date' => now()->endOfWeek()->toDateString(),
                'active' => true,
            ]);
        }

        return $goal;
    }

    /**
     * Create a new goal
     */
    public function createGoal(array $data)
    {
        return StudentGoal::create($data);
    }

    /**
     * Update goal progress
     */
    public function updateGoalProgress($goalId, $currentValue)
    {
        $goal = StudentGoal::findOrFail($goalId);
        return $goal->updateProgress($currentValue);
    }

    /**
     * Update weekly classes goal progress for a student
     */
    public function updateWeeklyClassesProgress($studentId, $establishmentId = null)
    {
        $goal = $this->getOrCreateWeeklyClassesGoal($studentId, $establishmentId);
        
        // Calculate current week classes
        $currentWeekClasses = ClassBooking::where('student_id', $studentId)
            ->whereBetween('created_at', [
                now()->startOfWeek()->toDateTimeString(),
                now()->endOfWeek()->toDateTimeString()
            ])
            ->count();

        // Update goal progress
        $goal->updateProgress($currentWeekClasses);

        return $goal;
    }

    /**
     * Update monthly classes goal progress
     */
    public function updateMonthlyClassesProgress($goalId)
    {
        $goal = StudentGoal::findOrFail($goalId);
        
        $currentMonthClasses = ClassBooking::where('student_id', $goal->student_id)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $goal->updateProgress($currentMonthClasses);

        return $goal;
    }

    /**
     * Update workout goals progress
     */
    public function updateWorkoutProgress($goalId)
    {
        $goal = StudentGoal::findOrFail($goalId);
        
        $workoutCount = WorkoutLog::where('student_id', $goal->student_id)
            ->whereBetween('date', [
                $goal->start_date->toDateString(),
                $goal->end_date ? $goal->end_date->toDateString() : now()->toDateString()
            ])
            ->count();

        $goal->updateProgress($workoutCount);

        return $goal;
    }

    /**
     * Update weight loss goal progress
     */
    public function updateWeightLossProgress($goalId)
    {
        $goal = StudentGoal::findOrFail($goalId);
        
        // Get initial weight (from first assessment on or before start_date)
        $initialAssessment = PhysicalAssessment::where('student_id', $goal->student_id)
            ->where('assessment_date', '<=', $goal->start_date)
            ->orderBy('assessment_date', 'desc')
            ->first();

        // Get current weight (from most recent assessment)
        $currentAssessment = PhysicalAssessment::where('student_id', $goal->student_id)
            ->where('assessment_date', '>=', $goal->start_date)
            ->orderBy('assessment_date', 'desc')
            ->first();

        if ($initialAssessment && $currentAssessment && $initialAssessment->weight) {
            $weightLost = $initialAssessment->weight - $currentAssessment->weight;
            $goal->updateProgress($weightLost);
        }

        return $goal;
    }

    /**
     * Get active goals for a student
     */
    public function getActiveGoals($studentId, $establishmentId = null)
    {
        $query = StudentGoal::where('student_id', $studentId)
            ->where('active', true)
            ->where(function($q) use ($establishmentId) {
                if ($establishmentId) {
                    $q->where('establishment_id', $establishmentId)->orWhereNull('establishment_id');
                } else {
                    $q->whereNull('establishment_id');
                }
            })
            ->whereDate('start_date', '<=', now()->toDateString())
            ->where(function($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now()->toDateString());
            })
            ->orderBy('created_at', 'desc');

        return $query->get();
    }

    /**
     * Sync all goals progress for a student
     */
    public function syncAllGoalsProgress($studentId, $establishmentId = null)
    {
        $goals = $this->getActiveGoals($studentId, $establishmentId);

        foreach ($goals as $goal) {
            switch ($goal->goal_type) {
                case 'weekly_classes':
                    $this->updateWeeklyClassesProgress($studentId, $establishmentId);
                    break;
                case 'monthly_classes':
                    $this->updateMonthlyClassesProgress($goal->id);
                    break;
                case 'workouts':
                case 'weekly_workouts':
                case 'monthly_workouts':
                    $this->updateWorkoutProgress($goal->id);
                    break;
                case 'weight_loss':
                case 'weight_gain':
                    $this->updateWeightLossProgress($goal->id);
                    break;
            }
        }

        return $goals;
    }

    /**
     * Check and mark achieved goals
     */
    public function checkAchievedGoals($studentId, $establishmentId = null)
    {
        $goals = $this->getActiveGoals($studentId, $establishmentId);
        $achievedGoals = [];

        foreach ($goals as $goal) {
            if ($goal->current_value >= $goal->target_value && !$goal->achieved) {
                $goal->achieved = true;
                $goal->achieved_at = now();
                $goal->save();
                $achievedGoals[] = $goal;
            }
        }

        return $achievedGoals;
    }

    /**
     * Get goal statistics for a student
     */
    public function getGoalStatistics($studentId, $establishmentId = null)
    {
        $activeGoals = $this->getActiveGoals($studentId, $establishmentId);
        $achievedGoals = StudentGoal::where('student_id', $studentId)
            ->where('achieved', true)
            ->count();
        $totalGoals = StudentGoal::where('student_id', $studentId)->count();

        return [
            'active' => $activeGoals->count(),
            'achieved' => $achievedGoals,
            'total' => $totalGoals,
            'achievement_rate' => $totalGoals > 0 ? ($achievedGoals / $totalGoals) * 100 : 0,
            'goals' => $activeGoals,
        ];
    }
}

