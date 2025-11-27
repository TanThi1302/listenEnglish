<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Category;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // Dashboard statistics
        $stats = [
            'total_lessons' => Lesson::count(),
            'total_users' => User::count(),
            'total_categories' => Category::count(),
            'lessons_this_week' => Lesson::where('created_at', '>=', Carbon::now()->subWeek())->count(),
        ];
        
        // Lessons by difficulty
        $lessonsByDifficulty = Lesson::selectRaw('difficulty_level, COUNT(*) as count')
            ->groupBy('difficulty_level')
            ->pluck('count', 'difficulty_level')
            ->toArray();
        
        // Recent activities
        $recentLessons = Lesson::latest()->take(3)->get();
        $recentUsers = User::latest()->take(3)->get();
        
        return view('admin.dashboard', compact('stats', 'lessonsByDifficulty', 'recentLessons', 'recentUsers'));
    }
    
    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }
    
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
}