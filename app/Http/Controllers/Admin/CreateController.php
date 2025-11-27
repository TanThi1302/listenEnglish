<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CreateController extends Controller
{
    public function create()
    {
        $sections = Section::with('category')->get();
        return view('admin.lessons.create', compact('sections'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255|unique:lessons,title',
            'section_id' => 'required|exists:sections,id',
            'difficulty_level' => 'required|in:A1,A2,B1,B2,C1,C2',
            'description' => 'nullable|max:1000',
            'is_premium' => 'boolean',
            'total_parts' => 'required|integer|min:1',
        ]);
        
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_premium'] = $request->has('is_premium') ? 1 : 0;
        
        $lesson = Lesson::create($validated);
        
        return redirect()->route('admin.lessons.index')
            ->with('success', 'Lesson "' . $lesson->title . '" created successfully!');
    }
}