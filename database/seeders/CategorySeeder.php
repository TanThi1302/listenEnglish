<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Section;
use App\Models\Lesson;
use App\Models\LessonPart;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Short Stories',
                'slug' => 'short-stories',
                'description' => 'Practice English listening with engaging short stories',
                'order_index' => 1
            ],
            [
                'name' => 'Conversations',
                'slug' => 'conversations',
                'description' => 'Real-life conversation practice',
                'order_index' => 2
            ],
            [
                'name' => 'TOEIC Listening',
                'slug' => 'toeic-listening',
                'description' => 'TOEIC test preparation materials',
                'order_index' => 3
            ],
            [
                'name' => 'IELTS Listening',
                'slug' => 'ielts-listening',
                'description' => 'IELTS test preparation materials',
                'order_index' => 4
            ],
            [
                'name' => 'TED Talks',
                'slug' => 'ted-talks',
                'description' => 'Learn from inspiring TED talks',
                'order_index' => 5
            ],
            [
                'name' => 'Numbers',
                'slug' => 'numbers',
                'description' => 'Practice listening to numbers',
                'order_index' => 6
            ]
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);

            // Create sections for each category
            for ($i = 1; $i <= 3; $i++) {
                $section = Section::create([
                    'category_id' => $category->id,
                    'name' => "Section $i",
                    'order_index' => $i
                ]);

                // Create lessons for each section
                $this->createLessonsForSection($section, $i);
            }
        }
    }

    private function createLessonsForSection($section, $sectionNumber)
    {
        $lessons = [
            ['title' => 'First snowfall', 'level' => 'A1', 'parts' => 21],
            ['title' => "Jessica's first day of school", 'level' => 'A1', 'parts' => 25],
            ['title' => 'My flower garden', 'level' => 'A1', 'parts' => 19],
            ['title' => 'Going camping', 'level' => 'A2', 'parts' => 22],
            ['title' => 'My house', 'level' => 'A2', 'parts' => 21],
            ['title' => 'My first pet', 'level' => 'B1', 'parts' => 21],
            ['title' => 'Summer vacation', 'level' => 'B1', 'parts' => 23],
            ['title' => 'Daily schedule', 'level' => 'B2', 'parts' => 31],
        ];

        foreach ($lessons as $index => $lessonData) {
            $lesson = Lesson::create([
                'section_id' => $section->id,
                'title' => $lessonData['title'] . " (Section $sectionNumber)",
                'slug' => \Illuminate\Support\Str::slug($lessonData['title'] . " section $sectionNumber"),
                'description' => "Practice English listening with: {$lessonData['title']}",
                'difficulty_level' => $lessonData['level'],
                'total_parts' => $lessonData['parts'],
                'order_index' => $index + 1,
                'is_premium' => $index > 2 // First 3 lessons are free
            ]);

            // Create sample lesson parts
            $this->createLessonParts($lesson, $lessonData['parts']);
        }
    }

    private function createLessonParts($lesson, $totalParts)
    {
        $sampleTexts = [
            "I am a student.",
            "Can you help me, please?",
            "I don't understand.",
            "Could you say that again?",
            "What time is it?",
            "How are you today?",
            "Nice to meet you.",
            "Thank you very much.",
            "You're welcome.",
            "Have a great day!",
            "See you tomorrow.",
            "I'm learning English.",
            "This is very interesting.",
            "I like this lesson.",
            "Let's practice together.",
            "That sounds good.",
            "I agree with you.",
            "What do you think?",
            "Can you repeat that?",
            "I'm not sure about this.",
            "Let me try again.",
            "That was helpful.",
            "I understand now.",
            "This is challenging.",
            "I'm making progress.",
            "Keep up the good work!",
            "You did well today.",
            "Practice makes perfect.",
            "Don't give up!",
            "You can do it!",
            "Believe in yourself."
        ];

        for ($i = 1; $i <= min($totalParts, 30); $i++) {
            LessonPart::create([
                'lesson_id' => $lesson->id,
                'part_number' => $i,
                'text_content' => $sampleTexts[$i - 1] ?? "Sample text part $i",
                'audio_file' => "/audio/sample/part-{$i}.mp3",
                'hints' => "Listen carefully to the pronunciation",
                'explanation' => "This is a common English phrase used in everyday conversation."
            ]);
        }
    }
}
