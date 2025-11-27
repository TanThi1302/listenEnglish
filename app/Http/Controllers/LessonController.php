<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson; 
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function show($slug)
    {
        $lesson = Lesson::where('slug', $slug)
            ->with(['section.category', 'vocabulary', 'comments.user'])
            ->firstOrFail();

        $userProgress = null;
        $isBookmarked = false;

        if (Auth::check()) {
            $userProgress = $lesson->getUserProgress(Auth::id());
            $isBookmarked = $lesson->isBookmarkedBy(Auth::id());
        }

        return view('lessons.show', compact('lesson', 'userProgress', 'isBookmarked'));
    }

    public function bookmark(Request $request, $id)
    {
        $lesson = Lesson::findOrFail($id);
        $user = Auth::user();

        $bookmark = $user->bookmarks()->where('lesson_id', $id)->first();

        if ($bookmark) {
            $bookmark->delete();
            return response()->json(['bookmarked' => false]);
        } else {
            $user->bookmarks()->create(['lesson_id' => $id]);
            return response()->json(['bookmarked' => true]);
        }
    }

    public function comment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|min:3|max:1000'
        ]);

        $lesson = Lesson::findOrFail($id);
        
        $comment = $lesson->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id
        ]);

        return response()->json([
            'success' => true,
            'comment' => $comment->load('user')
        ]);
    }
}
