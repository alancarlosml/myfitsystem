<?php

namespace App\Http\Controllers;

use App\Services\AchievementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
    protected $achievementService;

    public function __construct(AchievementService $achievementService)
    {
        $this->achievementService = $achievementService;
    }

    /**
     * Show all achievements (student view)
     */
    public function index()
    {
        $studentId = Auth::guard('student')->id();
        
        $achievements = $this->achievementService->getAchievementsByType($studentId);
        $statistics = $this->achievementService->getAchievementStatistics($studentId);
        $recentAchievements = $this->achievementService->getRecentAchievements($studentId, 5);

        return view('student.achievements.index', [
            'achievements' => $achievements,
            'statistics' => $statistics,
            'recentAchievements' => $recentAchievements,
        ]);
    }

    /**
     * Get achievements for a specific student (admin view)
     */
    public function show($studentId)
    {
        // TODO: Add authorization check
        
        $achievements = $this->achievementService->getAchievementsByType($studentId);
        $statistics = $this->achievementService->getAchievementStatistics($studentId);
        $recentAchievements = $this->achievementService->getRecentAchievements($studentId, 10);

        return response()->json([
            'achievements' => $achievements,
            'statistics' => $statistics,
            'recent' => $recentAchievements,
        ]);
    }

    /**
     * Manually check and award achievements (AJAX)
     */
    public function checkAchievements()
    {
        $studentId = Auth::guard('student')->id();
        
        $newlyEarned = $this->achievementService->checkAndAwardAchievements($studentId);
        
        return response()->json([
            'success' => true,
            'newly_earned' => $newlyEarned,
            'count' => count($newlyEarned),
        ]);
    }
}
