<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\User;
use App\Models\LessonPart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total counts
        $totalLessons = Lesson::count();
        $totalUsers = User::count();
        $premiumUsers = 0;
        $totalParts = LessonPart::count();

        // Recent additions
        $recentLessons = Lesson::where('created_at', '>=', Carbon::now()->subWeek())->count();
        $newUsers = User::whereDate('created_at', Carbon::today())->count();

        // Calculations
        $premiumPercentage = $totalUsers > 0 ? round(($premiumUsers / $totalUsers) * 100, 1) : 0;
        $avgPartsPerLesson = $totalLessons > 0 ? round($totalParts / $totalLessons, 1) : 0;

        // Lessons by difficulty level
        $lessonsByLevel = Lesson::select('difficulty_level', DB::raw('count(*) as count'))
            ->groupBy('difficulty_level')
            ->pluck('count', 'difficulty_level')
            ->toArray();

        // Ensure all levels exist in array
        $allLevels = ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'];
        foreach ($allLevels as $level) {
            if (!isset($lessonsByLevel[$level])) {
                $lessonsByLevel[$level] = 0;
            }
        }

        // Recent activity (you can customize this based on your needs)
        $recentActivities = collect([
            (object)[
                'description' => 'New lesson created: ' . Lesson::latest()->first()?->title ?? 'N/A',
                'created_at' => Lesson::latest()->first()?->created_at ?? now()
            ],
            (object)[
                'description' => 'New user registered',
                'created_at' => User::latest()->first()?->created_at ?? now()
            ],
            (object)[
                'description' => $recentLessons . ' lessons added this week',
                'created_at' => now()->subDays(2)
            ],
        ])->filter();

        return view('dashboard', compact(
            'totalLessons',
            'totalUsers',
            'premiumUsers',
            'totalParts',
            'recentLessons',
            'newUsers',
            'premiumPercentage',
            'avgPartsPerLesson',
            'lessonsByLevel',
            'recentActivities'
        ));
    }
}