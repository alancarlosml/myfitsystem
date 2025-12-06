<?php

namespace App\Services;

use App\Models\Workout;
use App\Models\Exercise;
use App\Models\WorkoutLog;
use App\Services\AchievementService;

class WorkoutService
{
    protected $achievementService;

    public function __construct(AchievementService $achievementService)
    {
        $this->achievementService = $achievementService;
    }

    /**
     * Prepare exercises for a workout session
     */
    public function prepareExercisesForWorkout($workout)
    {
        // Buscar workouts relacionados do mesmo aluno e estabelecimento
        // Considera workouts criados no mesmo dia ou período próximo
        $relatedWorkouts = Workout::where('student_id', $workout->student_id)
            ->where('establishment_id', $workout->establishment_id)
            ->where('id', '!=', $workout->id)
            ->whereDate('created_at', $workout->created_at->toDateString())
            ->with(['exercise.category'])
            ->orderBy('order', 'asc')
            ->get();

        $exercises = [];

        // Adicionar o workout atual primeiro
        if ($workout->exercise) {
            $exercises[] = [
                'id' => $workout->exercise->id,
                'name' => $workout->exercise->name,
                'description' => $workout->exercise->description ?? 'Sem descrição',
                'category' => $workout->exercise->category?->name ?? 'Sem categoria',
                'sets' => $workout->sets ?? 3,
                'reps' => $workout->repetitions ?? 12,
                'rest_time' => $workout->rest_time ?? 60, // em segundos
                'duration' => max(1, round(($workout->rest_time ?? 60) / 60)), // minutos estimados por série
                'picture' => $workout->exercise->exercise_picture ?? null,
                'youtube_video_url' => $workout->exercise->youtube_video_url ?? null,
                'workout_id' => $workout->id,
                'order' => $workout->order ?? 0,
            ];
        }

        // Adicionar workouts relacionados
        foreach ($relatedWorkouts as $relatedWorkout) {
            if ($relatedWorkout->exercise) {
                $exercises[] = [
                    'id' => $relatedWorkout->exercise->id,
                    'name' => $relatedWorkout->exercise->name,
                    'description' => $relatedWorkout->exercise->description ?? 'Sem descrição',
                    'category' => $relatedWorkout->exercise->category?->name ?? 'Sem categoria',
                    'sets' => $relatedWorkout->sets ?? 3,
                    'reps' => $relatedWorkout->repetitions ?? 12,
                    'rest_time' => $relatedWorkout->rest_time ?? 60,
                    'duration' => max(1, round(($relatedWorkout->rest_time ?? 60) / 60)),
                    'picture' => $relatedWorkout->exercise->exercise_picture ?? null,
                    'youtube_video_url' => $relatedWorkout->exercise->youtube_video_url ?? null,
                    'workout_id' => $relatedWorkout->id,
                    'order' => $relatedWorkout->order ?? 0,
                ];
            }
        }

        // Se não houver exercícios, buscar até 5 exercícios ativos do estabelecimento
        if (empty($exercises)) {
            $availableExercises = Exercise::where('establishment_id', $workout->establishment_id)
                ->where('active', true)
                ->with('category')
                ->limit(5)
                ->get();

            foreach ($availableExercises as $exercise) {
                $exercises[] = [
                    'id' => $exercise->id,
                    'name' => $exercise->name,
                    'description' => $exercise->description ?? 'Sem descrição',
                    'category' => $exercise->category?->name ?? 'Sem categoria',
                    'sets' => 3, // Default
                    'reps' => 12, // Default
                    'rest_time' => 60, // Default
                    'duration' => 1, // Default
                    'picture' => $exercise->exercise_picture ?? null,
                    'youtube_video_url' => $exercise->youtube_video_url ?? null,
                    'workout_id' => null,
                    'order' => 0,
                ];
            }
        }

        // Ordenar por order
        usort($exercises, function ($a, $b) {
            return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
        });

        return $exercises;
    }

    /**
     * Log an exercise completion
     */
    public function logExercise($workoutId, $exerciseId, $studentId, array $data)
    {
        $workout = Workout::findOrFail($workoutId);
        
        $workoutLog = WorkoutLog::create([
            'workout_id' => $workoutId,
            'exercise_id' => $exerciseId,
            'student_id' => $studentId,
            'establishment_id' => $workout->establishment_id,
            'completed_sets' => $data['completed_sets'] ?? 0,
            'completed_reps' => $data['completed_reps'] ?? '',
            'rest_duration' => $data['rest_duration'] ?? 0,
            'exercise_duration' => $data['exercise_duration'] ?? 0,
            'notes' => $data['notes'] ?? '',
            'date' => $data['date'] ?? now()->toDateString(),
            'logged_at' => now(),
        ]);

        // Check and award achievements after logging a workout
        $this->achievementService->checkAndAwardAchievements($studentId);

        return $workoutLog;
    }

    /**
     * Get workout statistics for a student
     */
    public function getStudentWorkoutStats($studentId, $establishmentId = null)
    {
        $query = WorkoutLog::where('student_id', $studentId);
        
        if ($establishmentId) {
            $query->where('establishment_id', $establishmentId);
        }

        $totalWorkouts = $query->count();
        $thisMonth = $query->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->count();
        $thisWeek = $query->whereBetween('date', [
            now()->startOfWeek()->toDateString(),
            now()->endOfWeek()->toDateString()
        ])->count();

        return [
            'total' => $totalWorkouts,
            'this_month' => $thisMonth,
            'this_week' => $thisWeek,
        ];
    }

    /**
     * Get recent workout logs for a student
     */
    public function getRecentWorkoutLogs($studentId, $limit = 10, $establishmentId = null)
    {
        $query = WorkoutLog::with(['workout', 'exercise', 'exercise.category'])
            ->where('student_id', $studentId)
            ->orderBy('date', 'desc')
            ->orderBy('logged_at', 'desc');
        
        if ($establishmentId) {
            $query->where('establishment_id', $establishmentId);
        }

        return $query->limit($limit)->get();
    }
}

