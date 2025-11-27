<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->withCount('lessons')
            ->orderBy('order_index')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->with(['sections.lessons' => function($query) {
                $query->orderBy('order_index');
            }])
            ->firstOrFail();

        return view('categories.show', compact('category'));
    }
}
