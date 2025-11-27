@extends('layouts.app')

@section('title', $category->name . ' - English Dictation')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-2">
            <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600 transition">Home</a></li>
            <li><span class="mx-2 text-gray-400">/</span></li>
            <li><a href="{{ route('categories.index') }}" class="text-gray-600 hover:text-blue-600 transition">Categories</a></li>
            <li><span class="mx-2 text-gray-400">/</span></li>
            <li><span class="text-gray-900 font-semibold">{{ $category->name }}</span></li>
        </ol>
    </nav>

    <!-- Category Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-3xl p-12 mb-12 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-5xl font-extrabold mb-4">{{ $category->name }}</h1>
                <p class="text-xl opacity-90 mb-6">{{ $category->description }}</p>
                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-2">
                        <span class="text-3xl">📖</span>
                        <span class="text-lg font-semibold">{{ $category->lessons()->count() }} Lessons</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-3xl">📚</span>
                        <span class="text-lg font-semibold">{{ $category->sections->count() }} Sections</span>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block">
                <span class="text-9xl">📚</span>
            </div>
        </div>
    </div>

    <!-- Sections & Lessons -->
    @forelse($category->sections as $section)
    <div class="mb-12">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <!-- Section Header -->
            <div class="flex items-center justify-between mb-8 pb-6 border-b-2 border-gray-100">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">
                        {{ $section->name }}
                    </h2>
                    <p class="text-gray-600">{{ $section->lessons->count() }} lessons available</p>
                </div>
                <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full font-bold">
                    Section {{ $loop->iteration }}
                </span>
            </div>

            <!-- Lessons Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($section->lessons as $lesson)
                <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-blue-500 hover:shadow-xl transition-all duration-300">
                    <!-- Lesson Number & Level -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-2xl font-bold text-gray-400">
                            {{ $loop->parent->iteration }}.{{ $loop->iteration }}
                        </span>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-bold rounded-full">
                            {{ $lesson->difficulty_level }}
                        </span>
                    </div>

                    <!-- Premium Badge -->
                    @if($lesson->is_premium)
                    <div class="inline-flex items-center space-x-1 px-3 py-1 bg-gradient-to-r from-yellow-400 to-orange-400 text-white text-xs font-bold rounded-full mb-3">
                        <span>💎</span>
                        <span>PREMIUM</span>
                    </div>
                    @endif

                    <!-- Title -->
                    <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                        {{ $lesson->title }}
                    </h3>

                    <!-- Info -->
                    <div class="flex items-center space-x-4 text-sm text-gray-600 mb-4">
                        <span class="flex items-center space-x-1">
                            <span>📝</span>
                            <span>{{ $lesson->total_parts }} parts</span>
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <a href="{{ route('lessons.show', $lesson->slug) }}" 
                           class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 text-center rounded-lg hover:bg-gray-200 transition font-medium text-sm">
                            View Details
                        </a>
                        @auth
                        <a href="{{ route('lessons.learn', $lesson->slug) }}" 
                           class="flex-1 px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition font-bold text-sm">
                            Start Now →
                        </a>
                        @else
                        <a href="{{ route('login') }}" 
                           class="flex-1 px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition font-bold text-sm">
                            Login
                        </a>
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl shadow-lg p-20 text-center">
        <span class="text-8xl mb-6 block">📭</span>
        <h3 class="text-3xl font-bold text-gray-900 mb-4">No Lessons Yet</h3>
        <p class="text-xl text-gray-600 mb-8">This category is still being prepared. Check back soon!</p>
        <a href="{{ route('categories.index') }}" class="inline-block px-8 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition">
            ← Back to Categories
        </a>
    </div>
    @endforelse
</div>
@endsection