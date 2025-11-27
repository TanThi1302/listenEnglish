@extends('layouts.app')

@section('title', 'Create New Lesson')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Create New Lesson</h1>
                <p class="mt-2 text-gray-600">Add a new lesson to your platform</p>
            </div>
            <a href="{{ route('admin.lessons.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                ← Back to Lessons
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <form action="{{ route('admin.lessons.store') }}" method="POST">
                @csrf

                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Lesson Title <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        value="{{ old('title') }}"
                        placeholder="e.g., A day at the park"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror"
                        required
                    >
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Section -->
                <div class="mb-6">
                    <label for="section_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Section <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="section_id" 
                        id="section_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('section_id') border-red-500 @enderror"
                        required
                    >
                        <option value="">Select a section</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->category->name }} - {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('section_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Difficulty Level -->
                <div class="mb-6">
                    <label for="difficulty_level" class="block text-sm font-medium text-gray-700 mb-2">
                        Difficulty Level <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
                        @foreach(['A1', 'A2', 'B1', 'B2', 'C1', 'C2'] as $level)
                        <label class="relative">
                            <input 
                                type="radio" 
                                name="difficulty_level" 
                                value="{{ $level }}"
                                {{ old('difficulty_level') == $level ? 'checked' : '' }}
                                class="peer sr-only"
                                required
                            >
                            <div class="cursor-pointer border-2 border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition">
                                <div class="font-bold text-lg text-gray-900">{{ $level }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('difficulty_level')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Total Parts -->
                <div class="mb-6">
                    <label for="total_parts" class="block text-sm font-medium text-gray-700 mb-2">
                        Number of Parts <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="total_parts" 
                        id="total_parts" 
                        value="{{ old('total_parts', 1) }}"
                        min="1"
                        max="100"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('total_parts') border-red-500 @enderror"
                        required
                    >
                    @error('total_parts')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="4"
                        placeholder="Brief description of the lesson..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Premium Checkbox -->
                <div class="mb-8">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="is_premium" 
                            value="1"
                            {{ old('is_premium') ? 'checked' : '' }}
                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        >
                        <span class="text-sm font-medium text-gray-700">
                            Premium Lesson (Requires subscription)
                        </span>
                    </label>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4 pt-6 border-t">
                    <a 
                        href="{{ route('admin.lessons.index') }}" 
                        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition"
                    >
                        Cancel
                    </a>
                    <button 
                        type="submit" 
                        class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-lg"
                    >
                        Create Lesson
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection