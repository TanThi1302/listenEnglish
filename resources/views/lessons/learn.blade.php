@extends('layouts.app')

@section('title', $lesson->title . ' - Learn')

@section('content')
<div class="max-w-6xl mx-auto px-4" x-data="learningApp()">
    <!-- Header with Progress -->
    <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 sticky top-20 z-40">
        <div class="flex items-center justify-between mb-4">
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $lesson->title }}</h1>
                <p class="text-gray-600 text-sm">{{ $lesson->section->category->name }} • {{ $lesson->difficulty_level }}</p>
            </div>
            <a href="{{ route('lessons.show', $lesson->slug) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                ← Exit
            </a>
        </div>
        
        <!-- Progress Bar -->
        <div class="mb-4">
            <div class="flex justify-between text-sm font-medium text-gray-700 mb-2">
                <span>Progress</span>
                <span x-text="`Part ${currentPart} of ${totalParts}`"></span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-3 rounded-full transition-all duration-500" 
                     :style="`width: ${(currentPart / totalParts) * 100}%`"></div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-4 gap-3">
            <div class="bg-green-50 rounded-lg p-3 text-center">
                <p class="text-2xl font-bold text-green-600" x-text="correctCount"></p>
                <p class="text-xs text-green-700 font-medium">Correct</p>
            </div>
            <div class="bg-red-50 rounded-lg p-3 text-center">
                <p class="text-2xl font-bold text-red-600" x-text="wrongCount"></p>
                <p class="text-xs text-red-700 font-medium">Wrong</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-3 text-center">
                <p class="text-2xl font-bold text-blue-600" x-text="accuracy + '%'"></p>
                <p class="text-xs text-blue-700 font-medium">Accuracy</p>
            </div>
            <div class="bg-purple-50 rounded-lg p-3 text-center">
                <p class="text-2xl font-bold text-purple-600" x-text="playCount"></p>
                <p class="text-xs text-purple-700 font-medium">Plays</p>
            </div>
        </div>
    </div>

    <!-- Main Learning Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Audio Player Section (2/3 width) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-6">
                <div class="text-center mb-8">
                    <p class="text-lg text-gray-700 mb-6 font-medium">🎧 Listen carefully and type what you hear</p>
                    
                    <!-- Play Button -->
                    <button @click="playAudio()" 
                            class="relative w-24 h-24 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white flex items-center justify-center mx-auto mb-6 shadow-2xl transition-all duration-300 transform hover:scale-110 group">
                        <span x-show="!isPlaying" class="text-4xl group-hover:scale-110 transition">▶</span>
                        <span x-show="isPlaying" class="text-4xl">⏸</span>
                        <div class="absolute inset-0 rounded-full bg-blue-400 opacity-50 animate-ping" x-show="isPlaying"></div>
                    </button>

                    <!-- Audio Controls -->
                    <div class="flex items-center justify-center gap-3 mb-6">
                        <span class="text-sm font-medium text-gray-600">Speed:</span>
                        <button @click="changeSpeed(0.75)" 
                                :class="speed === 0.75 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                                class="px-4 py-2 rounded-lg font-bold transition">
                            0.75x
                        </button>
                        <button @click="changeSpeed(1)" 
                                :class="speed === 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                                class="px-4 py-2 rounded-lg font-bold transition">
                            1x
                        </button>
                        <button @click="changeSpeed(1.25)" 
                                :class="speed === 1.25 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                                class="px-4 py-2 rounded-lg font-bold transition">
                            1.25x
                        </button>
                        <button @click="changeSpeed(1.5)" 
                                :class="speed === 1.5 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                                class="px-4 py-2 rounded-lg font-bold transition">
                            1.5x
                        </button>
                    </div>
                </div>

                <!-- Answer Input -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Your Answer:</label>
                    <textarea x-model="userAnswer" 
                              @keydown.ctrl.enter="checkAnswer()"
                              :disabled="showResult"
                              placeholder="Type what you hear... (Ctrl+Enter to check)"
                              class="w-full px-6 py-4 text-lg border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none resize-none transition"
                              rows="3"></textarea>
                    <p class="text-xs text-gray-500 mt-2">💡 Tip: Press Ctrl+Enter to quickly check your answer</p>
                </div>

                <!-- Result Message -->
                <div x-show="showResult" x-transition class="mb-6">
                    <div :class="isCorrect ? 'bg-green-50 border-green-500 text-green-900' : 'bg-red-50 border-red-500 text-red-900'" 
                         class="border-l-4 p-6 rounded-xl shadow-lg">
                        <div class="flex items-center mb-3">
                            <span x-show="isCorrect" class="text-3xl mr-3">✓</span>
                            <span x-show="!isCorrect" class="text-3xl mr-3">✗</span>
                            <p class="text-xl font-bold" x-text="isCorrect ? 'Perfect! Well done!' : 'Not quite right'"></p>
                        </div>
                        <div class="space-y-2">
                            <p class="font-medium">
                                <strong>Correct answer:</strong> <span x-text="correctAnswer" class="font-mono bg-white px-2 py-1 rounded"></span>
                            </p>
                            <p x-show="similarity">
                                <strong>Similarity:</strong> <span x-text="similarity + '%'" class="font-bold"></span>
                            </p>
                            <div x-show="!isCorrect" class="mt-3 p-3 bg-yellow-50 rounded-lg">
                                <p class="text-sm text-yellow-800">🔍 Compare your answer with the correct one carefully</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-3 gap-3">
                    <button @click="getHint()" 
                            :disabled="showResult || hintUsed"
                            class="px-6 py-4 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 disabled:bg-gray-300 disabled:cursor-not-allowed transition font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 disabled:transform-none">
                            💡 Hint
                    </button>
                    
                    <button @click="checkAnswer()" 
                            x-show="!showResult"
                            :disabled="!userAnswer.trim()"
                            class="px-6 py-4 bg-blue-600 text-white rounded-xl hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 disabled:transform-none">
                            ✓ Check
                    </button>
                    
                    <button @click="nextPart()" 
                            x-show="showResult"
                            class="px-6 py-4 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            Next →
                    </button>
                    
                    <button @click="skipPart()" 
                            class="px-6 py-4 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            ⏭ Skip
                    </button>
                </div>

                <!-- Hint Display -->
                <div x-show="hintText" x-transition class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-xl">
                    <p class="font-bold text-yellow-900 mb-2">💡 Hint:</p>
                    <p class="text-yellow-800" x-text="hintText"></p>
                </div>
            </div>
        </div>

        <!-- Sidebar (1/3 width) -->
        <div class="lg:col-span-1">
            <!-- Quick Tips -->
            <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-6 mb-6 shadow-lg">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <span class="text-2xl mr-2">💡</span>
                    Quick Tips
                </h3>
                <ul class="space-y-3 text-sm text-gray-700">
                    <li class="flex items-start">
                        <span class="text-blue-500 mr-2">•</span>
                        <span>Listen multiple times before typing</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-500 mr-2">•</span>
                        <span>Adjust speed if needed</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-500 mr-2">•</span>
                        <span>Use hints wisely</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-500 mr-2">•</span>
                        <span>Press Ctrl+Enter to check</span>
                    </li>
                </ul>
            </div>

            <!-- Keyboard Shortcuts -->
            <div class="bg-white rounded-2xl p-6 shadow-lg">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <span class="text-2xl mr-2">⌨️</span>
                    Shortcuts
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                        <span class="text-gray-600">Play/Pause</span>
                        <kbd class="px-2 py-1 bg-gray-800 text-white rounded text-xs font-mono">Space</kbd>
                    </div>
                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                        <span class="text-gray-600">Check Answer</span>
                        <kbd class="px-2 py-1 bg-gray-800 text-white rounded text-xs font-mono">Ctrl+Enter</kbd>
                    </div>
                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                        <span class="text-gray-600">Get Hint</span>
                        <kbd class="px-2 py-1 bg-gray-800 text-white rounded text-xs font-mono">H</kbd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function learningApp() {
    return {
        lessonId: {{ $lesson->id }},
        parts: @json($lesson->parts),
        currentPart: 1,
        totalParts: {{ $lesson->total_parts }},
        
        userAnswer: '',
        correctAnswer: '',
        showResult: false,
        isCorrect: false,
        similarity: 0,
        
        correctCount: 0,
        wrongCount: 0,
        accuracy: 0,
        
        sound: null,
        isPlaying: false,
        playCount: 0,
        speed: 1,
        
        hintText: '',
        hintUsed: false,
        
        startTime: Date.now(),
        
        init() {
            this.loadAudio();
            this.setupKeyboardShortcuts();
        },
        
        setupKeyboardShortcuts() {
            document.addEventListener('keydown', (e) => {
                if (e.key === ' ' && e.target.tagName !== 'TEXTAREA') {
                    e.preventDefault();
                    this.playAudio();
                }
                if (e.key === 'h' && e.target.tagName !== 'TEXTAREA' && !this.showResult) {
                    this.getHint();
                }
            });
        },
        
        loadAudio() {
            const part = this.parts[this.currentPart - 1];
            if (!part) return;
            
            if (this.sound) {
                this.sound.unload();
            }
            
            this.sound = new Howl({
                src: ['/storage/' + part.audio_file],
                rate: this.speed,
                onend: () => {
                    this.isPlaying = false;
                },
                onloaderror: () => {
                    alert('Error loading audio file');
                }
            });
            
            this.resetState();
        },
        
        playAudio() {
            if (!this.sound) return;
            
            if (this.isPlaying) {
                this.sound.pause();
                this.isPlaying = false;
            } else {
                this.sound.play();
                this.isPlaying = true;
                this.playCount++;
            }
        },
        
        changeSpeed(newSpeed) {
            this.speed = newSpeed;
            if (this.sound) {
                this.sound.rate(newSpeed);
            }
        },
        
        async checkAnswer() {
    console.log('=== CHECK ANSWER START ===');
    console.log('User Answer:', this.userAnswer);
    
    if (!this.userAnswer.trim()) {
        alert('Please type your answer first!');
        return;
    }
    
    const part = this.parts[this.currentPart - 1];
    console.log('Current Part:', part);
    
    const timeTaken = Math.floor((Date.now() - this.startTime) / 1000);
    
    const payload = {
        lesson_part_id: part.id,
        user_answer: this.userAnswer,
        time_taken: timeTaken,
        hints_used: this.hintUsed ? 1 : 0
    };
    
    console.log('Sending payload:', payload);
    
    try {
        const response = await fetch('/learning/check-answer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payload)
        });
        
        console.log('Response status:', response.status);
        
        // QUAN TRỌNG: Kiểm tra response
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Server error:', errorText);
            throw new Error(`HTTP ${response.status}: ${errorText}`);
        }
        
        const data = await response.json();
        console.log('Response data:', data);
        
        this.showResult = true;
        this.isCorrect = data.correct;
        this.correctAnswer = data.correct_answer;
        this.similarity = data.similarity;
        
        if (this.isCorrect) {
            this.correctCount++;
        } else {
            this.wrongCount++;
        }
        
        this.updateAccuracy();
        
        console.log('=== CHECK ANSWER SUCCESS ===');
        
    } catch (error) {
        console.error('=== CHECK ANSWER ERROR ===');
        console.error('Error details:', error);
        console.error('Error message:', error.message);
        console.error('Error stack:', error.stack);
        
        alert('Error: ' + error.message + '\n\nCheck console (F12) for details.');
    }
},
        
        async getHint() {
            if (this.hintUsed) return;
            
            const part = this.parts[this.currentPart - 1];
            
            try {
                const response = await fetch(`/learning/hint/${part.id}`);
                const data = await response.json();
                
                this.hintText = data.hint;
                this.hintUsed = true;
                
            } catch (error) {
                console.error('Error:', error);
            }
        },
        
        nextPart() {
            if (this.currentPart >= this.totalParts) {
                if(confirm('🎉 Congratulations! You completed this lesson!\\n\\nWould you like to view your results?')) {
                    window.location.href = '/lessons/{{ $lesson->slug }}';
                }
                return;
            }
            
            this.currentPart++;
            this.loadAudio();
        },
        
        skipPart() {
            if(confirm('Are you sure you want to skip this part?')) {
                this.nextPart();
            }
        },
        
        resetState() {
            this.userAnswer = '';
            this.showResult = false;
            this.isCorrect = false;
            this.similarity = 0;
            this.hintText = '';
            this.hintUsed = false;
            this.playCount = 0;
            this.startTime = Date.now();
        },
        
        updateAccuracy() {
            const total = this.correctCount + this.wrongCount;
            this.accuracy = total > 0 ? Math.round((this.correctCount / total) * 100) : 0;
        }
    }
}
</script>
@endpush
@endsection