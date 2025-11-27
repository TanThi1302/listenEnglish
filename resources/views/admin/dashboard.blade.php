@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="mt-2 text-gray-600">Welcome back! Here's what's happening with your platform.</p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Create New Lesson -->
            <a href="{{ route('admin.lessons.create') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white bg-opacity-30 rounded-lg p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold mb-2">Create New Lesson</h3>
                <p class="text-blue-100 text-sm">Add a new lesson to your platform</p>
            </a>

            <!-- Manage Lessons -->
            <a href="{{ route('admin.lessons.index') }}" class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white bg-opacity-30 rounded-lg p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold mb-2">Manage Lessons</h3>
                <p class="text-green-100 text-sm">View and edit all lessons</p>
            </a>

            <!-- Manage Categories -->
            <a href="{{ route('admin.categories.index') }}" class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white bg-opacity-30 rounded-lg p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold mb-2">Manage Categories</h3>
                <p class="text-purple-100 text-sm">Organize lesson categories</p>
            </a>

            <!-- Manage Users -->
            <a href="{{ route('admin.users.index') }}" class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white bg-opacity-30 rounded-lg p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold mb-2">Manage Users</h3>
                <p class="text-orange-100 text-sm">View and manage users</p>
            </a>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm font-medium text-gray-600 mb-1">Total Lessons</div>
                <div class="text-3xl font-bold text-gray-900">{{ $stats['total_lessons'] }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm font-medium text-gray-600 mb-1">Total Users</div>
                <div class="text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm font-medium text-gray-600 mb-1">Total Categories</div>
                <div class="text-3xl font-bold text-gray-900">{{ $stats['total_categories'] }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm font-medium text-gray-600 mb-1">This Week</div>
                <div class="text-3xl font-bold text-green-600">{{ $stats['lessons_this_week'] }}</div>
            </div>
        </div>

        <!-- Lessons by Difficulty Level -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Lessons by Difficulty Level</h2>
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                @foreach(['A1', 'A2', 'B1', 'B2', 'C1', 'C2'] as $level)
                <div class="text-center">
                    <div class="text-sm text-gray-600 mb-2">{{ $level }}</div>
                    <div class="text-3xl font-bold text-blue-600">{{ $lessonsByDifficulty[$level] ?? 0 }}</div>
                    <div class="mt-2 bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($lessonsByDifficulty[$level] ?? 0) > 0 ? min(100, ($lessonsByDifficulty[$level] / max(array_values($lessonsByDifficulty))) * 100) : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Lessons -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Recent Lessons</h2>
                <div class="space-y-3">
                    @forelse($recentLessons as $lesson)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">{{ $lesson->title }}</div>
                            <div class="text-sm text-gray-500">{{ $lesson->created_at->diffForHumans() }}</div>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $lesson->difficulty_level }}
                        </span>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">No lessons yet</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Users -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Recent Users</h2>
                <div class="space-y-3">
                    @forelse($recentUsers as $user)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">{{ $user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        </div>
                        <div class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">No users yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection