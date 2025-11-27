<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\LessonPart;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class LessonManagementController extends Controller
{
    
    public function index()
    {
        $lessons = Lesson::with(['section.category', 'parts'])->latest()->paginate(15);
        return view('admin.lessons.index', compact('lessons'));
    }
    
    public function edit($id)
    {
        $lesson = Lesson::with('parts')->findOrFail($id);
        $sections = Section::with('category')->get();
        return view('admin.lessons.edit', compact('lesson', 'sections'));
    }
    
    public function update(Request $request, $id)
    {
        $lesson = Lesson::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|max:255|unique:lessons,title,' . $id,
            'section_id' => 'required|exists:sections,id',
            'difficulty_level' => 'required|in:A1,A2,B1,B2,C1,C2',
            'description' => 'nullable|max:1000',
            'is_premium' => 'boolean',
        ]);
        
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_premium'] = $request->has('is_premium') ? 1 : 0;
        
        $lesson->update($validated);
        
        return redirect()->route('admin.lessons.index')
            ->with('success', 'Lesson updated successfully!');
    }
    
    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id);
        
        // Delete associated audio files
        foreach ($lesson->parts as $part) {
            if ($part->audio_file && Storage::disk('public')->exists($part->audio_file)) {
                Storage::disk('public')->delete($part->audio_file);
            }
        }
        
        $lesson->delete();
        
        return redirect()->route('admin.lessons.index')
            ->with('success', 'Lesson deleted successfully!');
    }
    
    public function addPart(Request $request, $id)
    {
        $lesson = Lesson::findOrFail($id);
        
        $validated = $request->validate([
            'text_content' => 'required|max:500',
            'audio_file' => 'required|file|mimes:mp3,wav,ogg|max:10240',
            'hints' => 'nullable|max:500',
            'explanation' => 'nullable|max:1000',
        ]);
        
        // Upload audio file
        if ($request->hasFile('audio_file')) {
            $audioPath = $request->file('audio_file')->store('lesson-audios', 'public');
            $validated['audio_file'] = $audioPath;
        }
        
        // Auto increment part_number
        $validated['part_number'] = $lesson->parts()->max('part_number') + 1;
        
        $lesson->parts()->create($validated);
        
        return back()->with('success', 'Part added successfully!');
    }
    
    public function deletePart($id)
    {
        $part = LessonPart::findOrFail($id);
        
        // Delete audio file
        if ($part->audio_file && Storage::disk('public')->exists($part->audio_file)) {
            Storage::disk('public')->delete($part->audio_file);
        }
        
        $part->delete();
        
        return back()->with('success', 'Part deleted successfully!');
    }
}