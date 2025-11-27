<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        $totalLessons = UserProgress::where('user_id', $user->id)->count();
        $completedLessons = UserProgress::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();
        
        $recentProgress = UserProgress::where('user_id', $user->id)
            ->with('lesson.section.category')
            ->orderBy('last_accessed_at', 'desc')
            ->limit(10)
            ->get();

        $achievements = $user->achievements;
        $bookmarkedLessons = $user->bookmarks()->with('lesson')->get();

        return view('profile.index', compact(
            'user',
            'totalLessons',
            'completedLessons',
            'recentProgress',
            'achievements',
            'bookmarkedLessons'
        ));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|max:2048'
        ]);

        $data = $request->only(['name', 'email']);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', 'Password updated successfully!');
    }
}
