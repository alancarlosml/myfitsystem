<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\Student;
use App\Models\ClassBooking;
use App\Models\WorkoutLog;
use App\Models\PhysicalAssessment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AchievementService
{
    /**
     * Get all achievements earned by a student
     */
    public function getStudentAchievements($studentId)
    {
        return Student::findOrFail($studentId)->achievements;
    }

    /**
     * Get count of achievements earned by a student
     */
    public function getAchievementCount($studentId)
    {
        return Student::findOrFail($studentId)->achievements()->count();
    }

    /**
     * Check and award achievements for a student
     */
    public function checkAndAwardAchievements($studentId)
    {
        $student = Student::findOrFail($studentId);
        $newlyEarned = [];

        // Get all active achievements
        $achievements = Achievement::active()->get();

        foreach ($achievements as $achievement) {
            // Skip if already earned
            if ($achievement->isEarnedBy($studentId)) {
                continue;
            }

            // Check if student meets the requirements
            if ($this->meetsRequirement($student, $achievement)) {
                $this->awardAchievement($studentId, $achievement->id);
                $newlyEarned[] = $achievement;
            }
        }

        return $newlyEarned;
    }

    /**
     * Check if student meets achievement requirement
     */
    private function meetsRequirement(Student $student, Achievement $achievement)
    {
        if (!$achievement->required_value) {
            return false;
        }

        $studentId = $student->id;
        $currentValue = 0;

        switch ($achievement->type) {
            case 'classes':
                // Total classes or yearly classes
                if (str_contains(strtolower($achievement->name), 'ano') || 
                    str_contains(strtolower($achievement->description), 'ano')) {
                    $currentValue = ClassBooking::where('student_id', $studentId)
                        ->whereYear('created_at', now()->year)
                        ->count();
                } else {
                    $currentValue = ClassBooking::where('student_id', $studentId)->count();
                }
                break;

            case 'streak':
                // Streak in weeks (already calculated in weeks)
                $streakWeeks = $this->calculateStreakWeeks($studentId);
                $currentValue = $streakWeeks;
                break;

            case 'workouts':
                // Total workouts
                $currentValue = WorkoutLog::where('student_id', $studentId)->count();
                break;

            case 'assessments':
                // Total physical assessments
                $currentValue = PhysicalAssessment::where('student_id', $studentId)->count();
                break;

            case 'custom':
                // Custom achievements need manual verification
                return false;
        }

        return $currentValue >= $achievement->required_value;
    }

    /**
     * Calculate streak in weeks for a student (optimized - single query)
     */
    private function calculateStreakWeeks($studentId)
    {
        $currentWeek = now()->startOfWeek();
        $weeksBack = 52; // Check up to 52 weeks back
        
        // Get all weeks with activity in a single query (using same format)
        $classBookings = ClassBooking::where('student_id', $studentId)
            ->where('created_at', '>=', $currentWeek->copy()->subWeeks($weeksBack))
            ->selectRaw('YEARWEEK(created_at, 1) as week_year')
            ->distinct()
            ->pluck('week_year')
            ->toArray();
        
        $workoutLogs = WorkoutLog::where('student_id', $studentId)
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
            // Use YEARWEEK format to match database query
            $weekYear = (int) $checkWeek->format('YW'); // Year + ISO week number
            
            if (in_array($weekYear, $activeWeeks)) {
                $streakWeeks++;
                $checkWeek->subWeek();
            } else {
                break;
            }
        }
        
        return $streakWeeks;
    }

    /**
     * Award an achievement to a student
     */
    public function awardAchievement($studentId, $achievementId)
    {
        $student = Student::findOrFail($studentId);
        $achievement = Achievement::findOrFail($achievementId);

        // Check if already earned
        if ($achievement->isEarnedBy($studentId)) {
            return false;
        }

        // Award the achievement
        $student->achievements()->attach($achievement->id, [
            'earned_at' => now(),
        ]);

        return true;
    }

    /**
     * Get achievement statistics for a student
     */
    public function getAchievementStatistics($studentId)
    {
        $student = Student::findOrFail($studentId);
        $totalAchievements = Achievement::active()->count();
        $earnedAchievements = $student->achievements()->count();
        $progress = $totalAchievements > 0 
            ? ($earnedAchievements / $totalAchievements) * 100 
            : 0;

        return [
            'total' => $totalAchievements,
            'earned' => $earnedAchievements,
            'remaining' => $totalAchievements - $earnedAchievements,
            'progress' => round($progress, 1),
        ];
    }

    /**
     * Get achievements grouped by type
     */
    public function getAchievementsByType($studentId = null)
    {
        $query = Achievement::active()->orderBy('type')->orderBy('required_value');

        $achievements = $query->get();

        if ($studentId) {
            $student = Student::findOrFail($studentId);
            $earnedIds = $student->achievements()->pluck('achievements.id')->toArray();

            // Mark which achievements are earned
            $achievements->each(function($achievement) use ($earnedIds) {
                $achievement->is_earned = in_array($achievement->id, $earnedIds);
            });
        }

        return $achievements->groupBy('type');
    }

    /**
     * Get recent achievements for a student
     */
    public function getRecentAchievements($studentId, $limit = 5)
    {
        $student = Student::findOrFail($studentId);
        
        return $student->achievements()
            ->orderBy('achievement_student.earned_at', 'desc')
            ->limit($limit)
            ->get();
    }
}

