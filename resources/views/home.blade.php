@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <!-- Hero Section with Animation -->
    <div class="text-center mb-20">
        <div class="inline-block animate-bounce mb-6">
            <span class="text-8xl">🎧</span>
        </div>
        <h1 class="text-6xl font-extrabold text-gray-900 mb-6 leading-tight">
            Master English <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Listening</span>
        </h1>
        <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
            Practice with real English audio and improve your listening skills through interactive dictation exercises
        </p>
        <div class="flex justify-center gap-4">
            <a href="{{ route('categories.index') }}" class="px-8 py-4 bg-blue-600 text-white text-lg font-semibold rounded-xl hover:bg-blue-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                 Start Learning Now
            </a>
            @guest
            <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-blue-600 text-lg font-semibold rounded-xl border-2 border-blue-600 hover:bg-blue-50 transition">
                Sign Up Free
            </a>
            @endguest
        </div>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
        <div class="bg-white rounded-2xl p-8 shadow-lg text-center card-hover">
            <div class="text-5xl mb-4">📚</div>
            <h3 class="text-4xl font-bold text-blue-600 mb-2">{{ $categories->count() }}</h3>
            <p class="text-gray-600 font-medium">Categories Available</p>
        </div>
        <div class="bg-white rounded-2xl p-8 shadow-lg text-center card-hover">
            <div class="text-5xl mb-4">📖</div>
            <h3 class="text-4xl font-bold text-purple-600 mb-2">{{ $featuredLessons->count() * 20 }}+</h3>
            <p class="text-gray-600 font-medium">Lessons to Practice</p>
        </div>
        <div class="bg-white rounded-2xl p-8 shadow-lg text-center card-hover">
            <div class="text-5xl mb-4">👥</div>
            <h3 class="text-4xl font-bold text-green-600 mb-2">{{ $topUsers->count() }}+</h3>
            <p class="text-gray-600 font-medium">Active Learners</p>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="mb-20">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-4xl font-bold text-gray-900">Explore Categories</h2>
            <a href="{{ route('categories.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                View All →
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories->take(6) as $category)
            <a href="{{ route('categories.show', $category->slug) }}" class="block bg-white rounded-2xl shadow-lg overflow-hidden card-hover">
                <div class="h-48 gradient-bg flex items-center justify-center">
                    <span class="text-7xl">📚</span>
                </div>
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">
                        {{ $category->name }}
                    </h3>
                    <p class="text-gray-600 mb-4 line-clamp-2">
                        {{ $category->description }}
                    </p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                            {{ $category->lessons->count() }} lessons
                        </span>
                        <span class="text-blue-600 font-semibold">Explore →</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Featured Lessons -->
    <div class="mb-20">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-4xl font-bold text-gray-900">🌟 Popular Lessons</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredLessons as $lesson)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-4 py-1 bg-blue-100 text-blue-800 text-sm font-bold rounded-full">
                            {{ $lesson->difficulty_level }}
                        </span>
                        <span class="text-gray-500 text-sm font-medium">
                            {{ $lesson->total_parts }} parts
                        </span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        {{ $lesson->title }}
                    </h3>
                    <p class="text-gray-600 text-sm mb-4">
                        {{ $lesson->section->category->name }}
                    </p>
                    <div class="flex gap-2">
                        <a href="{{ route('lessons.show', $lesson->slug) }}" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 text-center rounded-lg hover:bg-gray-200 transition font-medium">
                            Details
                        </a>
                        @auth
                        <a href="{{ route('lessons.learn', $lesson->slug) }}" class="flex-1 px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition font-bold">
                            Start →
                        </a>
                        @else
                        <a href="{{ route('login') }}" class="flex-1 px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition font-bold">
                            Login
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Leaderboard Preview -->
    <div class="bg-white rounded-2xl shadow-xl p-8 mb-20">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-4xl font-bold text-gray-900">🏆 Top Learners</h2>
            <a href="{{ route('leaderboard') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                View Full Leaderboard →
            </a>
        </div>
        <div class="space-y-4">
            @foreach($topUsers->take(5) as $index => $user)
            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full {{ $index === 0 ? 'bg-yellow-100' : ($index === 1 ? 'bg-gray-200' : ($index === 2 ? 'bg-orange-100' : 'bg-gray-100')) }}">
                        <span class="text-2xl font-bold text-gray-700">
                            {{ $index + 1 }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-xl">👤</span>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ $user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $user->level ?? 'Beginner' }}</p>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-blue-600">
                        {{ $user->total_points }}
                    </p>
                    <p class="text-sm text-gray-600">points</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Features Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
        <div class="text-center p-8 bg-white rounded-2xl shadow-lg card-hover">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="text-4xl">🎧</span>
            </div>
            <h3 class="text-2xl font-bold mb-4">Real Audio</h3>
            <p class="text-gray-600">Listen to authentic English from native speakers with natural pronunciation</p>
        </div>
        <div class="text-center p-8 bg-white rounded-2xl shadow-lg card-hover">
            <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="text-4xl">✍️</span>
            </div>
            <h3 class="text-2xl font-bold mb-4">Interactive Practice</h3>
            <p class="text-gray-600">Type what you hear and get instant feedback on your accuracy</p>
        </div>
        <div class="text-center p-8 bg-white rounded-2xl shadow-lg card-hover">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="text-4xl">📊</span>
            </div>
            <h3 class="text-2xl font-bold mb-4">Track Progress</h3>
            <p class="text-gray-600">Monitor your improvement with detailed statistics and achievements</p>
        </div>
    </div>

    <!-- CTA Section -->
    @guest
    <div class="gradient-bg rounded-3xl p-12 text-center text-white shadow-2xl">
        <h2 class="text-4xl font-bold mb-4">Ready to Start Your Journey?</h2>
        <p class="text-xl mb-8 opacity-90">Join thousands of learners improving their English listening skills</p>
        <a href="{{ route('register') }}" class="inline-block px-10 py-4 bg-white text-blue-600 text-lg font-bold rounded-xl hover:bg-gray-100 transition shadow-lg">
            Sign Up for Free →
        </a>
    </div>
    @endguest
</div>
@endsection