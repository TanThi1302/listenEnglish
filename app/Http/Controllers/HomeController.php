<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', TRUE)
            ->orderBy('order_index')
            ->get();
        
        $featuredLessons = Lesson::with(['section.category'])
            ->where('is_premium', 0)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        $topUsers = User::where('role', 'user')
            ->orderBy('total_points', 'desc')
            ->limit(10)
            ->get();
        
        return view('home', compact('categories', 'featuredLessons', 'topUsers'));
        
    }
}

