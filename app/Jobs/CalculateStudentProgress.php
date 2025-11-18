<?php

namespace App\Jobs;

use App\Models\PhysicalAssessment;
use App\Models\Student;
use App\Services\GoalService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class CalculateStudentProgress implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $studentId;

    /**
     * Create a new job instance.
     */
    public function __construct($studentId)
    {
        $this->studentId = $studentId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $student = Student::find($this->studentId);
        if (!$student) {
            return;
        }

        // Get the last two assessments
        $assessments = PhysicalAssessment::where('student_id', $this->studentId)
            ->orderBy('assessment_date', 'desc')
            ->limit(2)
            ->get();

        if ($assessments->count() >= 2) {
            $current = $assessments->first();
            $previous = $assessments->last();

            $progress = [
                'weight_loss' => $this->calculateProgress($previous->weight, $current->weight),
                'muscle_gain' => $this->calculateProgress($previous->muscle_mass_percentage, $current->muscle_mass_percentage),
                'body_fat_improvement' => $this->calculateProgress($current->body_fat_percentage, $previous->body_fat_percentage), // inverse for improvement
            ];

            // Cache the progress for 30 days
            Cache::put("student_progress_{$this->studentId}", $progress, now()->addDays(30));
        }

        // Sync all goals progress
        $goalService = app(GoalService::class);
        $establishmentId = $student->establishments->first()->id ?? null;
        $goalService->syncAllGoalsProgress($this->studentId, $establishmentId);
        $goalService->checkAchievedGoals($this->studentId, $establishmentId);

        // Check and award achievements
        $achievementService = app(\App\Services\AchievementService::class);
        $achievementService->checkAndAwardAchievements($this->studentId);
    }

    /**
     * Calculate progress percentage between two values
     */
    private function calculateProgress($previousValue, $currentValue)
    {
        if (!$previousValue || !$currentValue) {
            return 0;
        }

        if ($previousValue == 0) {
            return $currentValue > 0 ? 100 : 0;
        }

        $change = $currentValue - $previousValue;
        $percentage = ($change / abs($previousValue)) * 100;

        return round($percentage, 1);
    }
}
