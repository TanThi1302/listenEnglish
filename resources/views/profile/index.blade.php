<!-- resources/views/profile/index.blade.php -->
@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">My Profile</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-center mb-6">
                    <div class="w-32 h-32 bg-blue-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <span class="text-4xl">👤</span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    <p class="mt-2 px-4 py-1 bg-blue-100 text-blue-800 rounded-full inline-block">
                        {{ $user->level ?? 'Beginner' }}
                    </p>
                </div>

                <div class="border-t pt-6">
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Points</span>
                            <span class="font-bold text-blue-600">{{ $user->total_points }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Lessons Started</span>
                            <span class="font-bold">{{ $totalLessons }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Completed</span>
                            <span class="font-bold text-green-600">{{ $completedLessons }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Completion Rate</span>
                            <span class="font-bold">
                                {{ $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0 }}%
                            </span>
                        </div>
                    </div>
                </div>

                <button class="w-full mt-6 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Edit Profile
                </button>
            </div>

            <!-- Achievements -->
            <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">🏆 Achievements</h3>
                <div class="space-y-3">
                    @forelse($achievements as $achievement)
                    <div class="flex items-center gap-3 p-3 bg-yellow-50 rounded-lg">
                        <span class="text-3xl">{{ $achievement->icon }}</span>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $achievement->name }}</p>
                            <p class="text-sm text-gray-600">{{ $achievement->description }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">No achievements yet. Keep learning!</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Progress & Bookmarks -->
        <div class="lg:col-span-2">
            <!-- Recent Progress -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">📚 Recent Activity</h3>
                <div class="space-y-4">
                    @forelse($recentProgress as $progress)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $progress->lesson->title }}</h4>
                            <p class="text-sm text-gray-600">{{ $progress->lesson->section->category->name }}</p>
                            <div class="mt-2">
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">
                                        {{ $progress->completed_parts }}/{{ $progress->lesson->total_parts }} parts
                                    </span>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded">
                                        {{ number_format($progress->accuracy_rate, 0) }}% accuracy
                                    </span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('lessons.learn', $progress->lesson->slug) }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 ml-4">
                            Continue
                        </a>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-8">No recent activity</p>
                    @endforelse
                </div>
            </div>

            <!-- Bookmarked Lessons -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">⭐ Bookmarked Lessons</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($bookmarkedLessons as $bookmark)
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-2">{{ $bookmark->lesson->title }}</h4>
                        <p class="text-sm text-gray-600 mb-3">{{ $bookmark->lesson->section->category->name }}</p>
                        <a href="{{ route('lessons.show', $bookmark->lesson->slug) }}" 
                           class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            View Lesson →
                        </a>
                    </div>
                    @empty
                    <div class="col-span-2 text-gray-500 text-center py-8">
                        No bookmarked lessons yet
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
        <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            ← Back to Site
        </a>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('admin.categories.index') }}" class="block p-6 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
            <div class="text-4xl mb-2">📚</div>
            <h3 class="text-xl font-bold">Categories</h3>
            <p class="text-sm opacity-90">Manage categories</p>
        </a>
        <a href="{{ route('admin.lessons.index') }}" class="block p-6 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
            <div class="text-4xl mb-2">📖</div>
            <h3 class="text-xl font-bold">Lessons</h3>
            <p class="text-sm opacity-90">Manage lessons</p>
        </a>
        <a href="{{ route('admin.users.index') }}" class="block p-6 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition">
            <div class="text-4xl mb-2">👥</div>
            <h3 class="text-xl font-bold">Users</h3>
            <p class="text-sm opacity-90">Manage users</p>
        </a>
        <a href="#" class="block p-6 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
            <div class="text-4xl mb-2">📊</div>
            <h3 class="text-xl font-bold">Reports</h3>
            <p class="text-sm opacity-90">View statistics</p>
        </a>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                </div>
                <div class="text-4xl">👥</div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Categories</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_categories'] }}</p>
                </div>
                <div class="text-4xl">📚</div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Lessons</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_lessons'] }}</p>
                </div>
                <div class="text-4xl">📖</div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Completions</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_completions'] }}</p>
                </div>
                <div class="text-4xl">✅</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Recent Users</h3>
            <div class="space-y-3">
                @foreach($recentUsers as $user)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">{{ $user->created_at->diffForHumans() }}</p>
                        <p class="text-xs text-blue-600">{{ $user->total_points }} pts</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Recent Activity</h3>
            <div class="space-y-3">
                @foreach($recentProgress as $progress)
                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="font-semibold text-gray-900">{{ $progress->user->name }}</p>
                    <p class="text-sm text-gray-600">{{ $progress->lesson->title }}</p>
                    <div class="mt-2 flex gap-2">
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">
                            {{ $progress->completed_parts }}/{{ $progress->lesson->total_parts }}
                        </span>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">
                            {{ number_format($progress->accuracy_rate, 0) }}%
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection