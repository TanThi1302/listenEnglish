@extends('layouts.app')

@section('title', 'All Categories - English Dictation')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-5xl font-extrabold text-gray-900 mb-4">
            📚 Browse All Categories
        </h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Choose a category that matches your learning goals and start improving your English listening skills
        </p>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($categories as $category)
        <a href="{{ route('categories.show', $category->slug) }}" 
           class="group block bg-white rounded-2xl shadow-lg overflow-hidden card-hover">
            <!-- Image/Icon -->
            <div class="h-56 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 flex items-center justify-center relative overflow-hidden">
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover object-center transition duration-500 group-hover:scale-60">
                @else
                    <span class="text-8xl group-hover:scale-110 transition duration-500">📚</span>
                @endif
                <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition"></div>
            </div>
            
            <!-- Content -->
            <div class="p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition">
                    {{ $category->name }}
                </h3>
                <p class="text-gray-600 mb-4 line-clamp-2">
                    {{ $category->description }}
                </p>
                
                <!-- Stats -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl">📖</span>
                        <span class="text-sm font-semibold text-gray-700">
                            {{ $category->lessons_count }} lessons
                        </span>
                    </div>
                    <span class="text-blue-600 font-bold group-hover:translate-x-2 transition-transform inline-flex items-center">
                        Explore
                        <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-3 text-center py-20">
            <span class="text-8xl mb-4 block">😔</span>
            <p class="text-2xl text-gray-600">No categories available yet.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

<!-- resources/views/categories/show.blade.php -->
