<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;          
use App\Models\LessonPart;       
use App\Models\UserProgress;     
use App\Models\UserAnswer; 
use Illuminate\Support\Facades\Auth;
class LearningController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function learn($slug)
    {
        $lesson = Lesson::where('slug', $slug)
            ->with(['parts', 'section.category'])
            ->firstOrFail();

        // Get or create user progress
        $progress = UserProgress::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'lesson_id' => $lesson->id
            ],
            [
                'status' => 'in_progress',
                'last_accessed_at' => now()
            ]
        );

        $progress->update(['last_accessed_at' => now()]);

        return view('lessons.learn', compact('lesson', 'progress'));
    }

    public function checkAnswer(Request $request)
    {
        // 1. Lấy dữ liệu
        $lessonPart = LessonPart::find($request->lesson_part_id);
        
        if (!$lessonPart) {
            return response()->json(['error' => 'Lesson part not found'], 404);
        }
        
        // 2. So sánh câu trả lời
        $correctAnswer = strtolower(trim($lessonPart->text_content));
        $userAnswer = strtolower(trim($request->user_answer));
        
        similar_text($correctAnswer, $userAnswer, $percent);
        $isCorrect = $percent >= 95;
        
        // 3. Đếm lỗi
        $mistakes = 0;
        $correctWords = explode(' ', $correctAnswer);
        $userWords = explode(' ', $userAnswer);
        
        for ($i = 0; $i < max(count($correctWords), count($userWords)); $i++) {
            $cWord = $correctWords[$i] ?? '';
            $uWord = $userWords[$i] ?? '';
            if ($cWord !== $uWord) {
                $mistakes++;
            }
        }
        
        // 4. Lưu vào database - ĐƠN GIẢN
        try {
            UserAnswer::create([
                'user_id' => auth()->id(),
                'lesson_part_id' => $lessonPart->id,
                'user_answer' => $request->user_answer,
                'is_correct' => $isCorrect ? 1 : 0,
                'mistakes_count' => $mistakes,
                'time_taken' => $request->time_taken ?? 0,
                'hints_used' => $request->hints_used ?? 0
            ]);
        } catch (\Exception $e) {
            \Log::error('Save error: ' . $e->getMessage());
        }
        
        // 5. Trả về kết quả
        return response()->json([
            'correct' => $isCorrect,
            'correct_answer' => $lessonPart->text_content,
            'similarity' => round($percent, 2)
        ]);
    }
    private function countMistakes($correct, $user)
    {
        $correctWords = explode(' ', $correct);
        $userWords = explode(' ', $user);
        
        $mistakes = 0;
        $maxLength = max(count($correctWords), count($userWords));
        
        for ($i = 0; $i < $maxLength; $i++) {
            $correctWord = $correctWords[$i] ?? '';
            $userWord = $userWords[$i] ?? '';
            
            if ($correctWord !== $userWord) {
                $mistakes++;
            }
        }
        
        return $mistakes;
    }

    private function updateProgress($lessonId, $isCorrect)
    {
        $progress = UserProgress::where('user_id', Auth::id())
            ->where('lesson_id', $lessonId)
            ->first();

        if (!$progress) return;

        $progress->increment('total_attempts');
        
        if ($isCorrect) {
            $progress->increment('completed_parts');
        }

        // Calculate accuracy
        $totalAnswers = UserAnswer::where('user_id', Auth::id())
            ->whereHas('lessonPart', function($query) use ($lessonId) {
                $query->where('lesson_id', $lessonId);
            })
            ->count();

        $correctAnswers = UserAnswer::where('user_id', Auth::id())
            ->where('is_correct', true)
            ->whereHas('lessonPart', function($query) use ($lessonId) {
                $query->where('lesson_id', $lessonId);
            })
            ->count();

        $accuracy = $totalAnswers > 0 ? ($correctAnswers / $totalAnswers) * 100 : 0;
        
        $progress->update([
            'accuracy_rate' => round($accuracy, 2)
        ]);

        // Check if lesson completed
        $lesson = Lesson::find($lessonId);
        if ($progress->completed_parts >= $lesson->total_parts) {
            $progress->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);

            // Award points
            $this->awardPoints($lesson);
        }
    }

    private function awardPoints($lesson)
    {
        $user = Auth::user();
        
        // Base points based on difficulty
        $pointsMap = [
            'A1' => 10,
            'A2' => 15,
            'B1' => 20,
            'B2' => 25,
            'C1' => 30,
            'C2' => 35
        ];

        $points = $pointsMap[$lesson->difficulty_level] ?? 10;
        
        $user->increment('total_points', $points);
    }

    public function getHint($partId)
    {
        $part = LessonPart::findOrFail($partId);
        
        return response()->json([
            'hint' => $part->hints ?? 'No hint available'
        ]);
    }
}
