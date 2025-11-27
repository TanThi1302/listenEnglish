@extends('layouts.app')

@section('title', $lesson->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ showVocab: false }">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <span class="mx-2 text-gray-400">/</span>
                        <a href="{{ route('categories.show', $lesson->section->category->slug) }}" 
                           class="text-gray-700 hover:text-blue-600">
                            {{ $lesson->section->category->name }}
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <span class="mx-2 text-gray-400">/</span>
                        <span class="text-gray-500">{{ $lesson->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Lesson Info -->
    <div class="bg-white rounded-lg shadow-md p-8 mb-6">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $lesson->title }}</h1>
                <p class="text-gray-600">{{ $lesson->section->category->name }} - {{ $lesson->section->name }}</p>
            </div>
            @auth
            <button onclick="toggleBookmark({{ $lesson->id }})" 
                    id="bookmark-btn"
                    class="px-4 py-2 {{ $isBookmarked ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700' }} rounded-lg hover:shadow transition">
                <span id="bookmark-icon">{{ $isBookmarked ? '⭐' : '☆' }}</span>
                <span id="bookmark-text">{{ $isBookmarked ? 'Bookmarked' : 'Bookmark' }}</span>
            </button>
            @endauth
        </div>

        <div class="flex flex-wrap gap-3 mb-6">
            <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full font-medium">
                {{ $lesson->difficulty_level }}
            </span>
            <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full">
                {{ $lesson->total_parts }} parts
            </span>
            @if($lesson->is_premium)
            <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full font-medium">
                💎 Premium
            </span>
            @endif
        </div>

        @if($lesson->description)
        <p class="text-gray-700 mb-6">{{ $lesson->description }}</p>
        @endif

        <!-- Progress (if user is logged in) -->
        @auth
            @if($userProgress)
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                <p class="text-blue-800 font-medium mb-2">Your Progress</p>
                <div class="flex items-center gap-4">
                    <div>
                        <span class="text-2xl font-bold text-blue-600">{{ $userProgress->completed_parts }}</span>
                        <span class="text-gray-600">/ {{ $lesson->total_parts }}</span>
                    </div>
                    <div class="flex-1">
                        <div class="w-full bg-blue-200 rounded-full h-3">
                            <div class="bg-blue-600 h-3 rounded-full" 
                                 style="width: {{ ($userProgress->completed_parts / $lesson->total_parts) * 100 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <span class="text-lg font-bold text-green-600">{{ number_format($userProgress->accuracy_rate, 0) }}%</span>
                        <span class="text-gray-600 text-sm">accuracy</span>
                    </div>
                </div>
            </div>
            @endif
        @endauth

        <!-- Action Buttons -->
        <div class="flex gap-4">
            @auth
            <a href="{{ route('lessons.learn', $lesson->slug) }}" 
               class="flex-1 px-6 py-3 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition font-semibold text-lg">
                {{ $userProgress ? 'Continue Learning' : 'Start Learning' }} →
            </a>
            @else
            <a href="{{ route('login') }}" 
               class="flex-1 px-6 py-3 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition font-semibold text-lg">
                Login to Start →
            </a>
            @endauth
        </div>
    </div>

    <!-- Vocabulary -->
    @if($lesson->vocabulary->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <button @click="showVocab = !showVocab" class="w-full flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900">
                📚 Vocabulary ({{ $lesson->vocabulary->count() }} words)
            </h3>
            <span x-text="showVocab ? '▼' : '▶'" class="text-gray-500"></span>
        </button>

        <div x-show="showVocab" x-transition class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($lesson->vocabulary as $vocab)
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="font-semibold text-gray-900 text-lg">{{ $vocab->word }}</p>
                @if($vocab->pronunciation)
                <p class="text-sm text-gray-600 italic">{{ $vocab->pronunciation }}</p>
                @endif
                <p class="text-gray-700 mt-2">{{ $vocab->meaning }}</p>
                @if($vocab->example_sentence)
                <p class="text-sm text-gray-600 mt-2 italic">"{{ $vocab->example_sentence }}"</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Comments -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-6">
            💬 Comments ({{ $lesson->comments->count() }})
        </h3>

        @auth
        <form id="comment-form" class="mb-8">
            @csrf
            <textarea id="comment-content" 
                      placeholder="Share your thoughts about this lesson..."
                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none resize-none"
                      rows="3"></textarea>
            <button type="submit" class="mt-2 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Post Comment
            </button>
        </form>
        @else
        <div class="mb-8 p-4 bg-gray-50 rounded-lg text-center">
            <p class="text-gray-600">
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium">Login</a>
                to post a comment
            </p>
        </div>
        @endauth

        <div id="comments-list" class="space-y-4">
            @forelse($lesson->comments as $comment)
            <div class="border-l-4 border-blue-500 pl-4 py-2">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">{{ $comment->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                        <p class="text-gray-700 mt-2">{{ $comment->content }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-sm text-gray-600">{{ $comment->likes_count }} likes</span>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-8">No comments yet. Be the first to comment!</p>
            @endforelse
        </div>
    </div>
</div>

@auth
@push('scripts')
<script>
async function toggleBookmark(lessonId) {
    try {
        const response = await fetch(`/lessons/${lessonId}/bookmark`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        const btn = document.getElementById('bookmark-btn');
        const icon = document.getElementById('bookmark-icon');
        const text = document.getElementById('bookmark-text');
        
        if (data.bookmarked) {
            btn.className = 'px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg hover:shadow transition';
            icon.textContent = '⭐';
            text.textContent = 'Bookmarked';
        } else {
            btn.className = 'px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:shadow transition';
            icon.textContent = '☆';
            text.textContent = 'Bookmark';
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

document.getElementById('comment-form')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const content = document.getElementById('comment-content').value;
    if (!content.trim()) return;
    
    try {
        const response = await fetch('/lessons/{{ $lesson->id }}/comment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ content })
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
    }
});
</script>
@endpush
@endauth
@endsection