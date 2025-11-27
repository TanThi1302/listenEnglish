<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Achievement;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $achievements = [
            [
                'name' => 'First Steps',
                'description' => 'Complete your first lesson',
                'icon' => '🎯',
                'requirement' => 'Complete 1 lesson',
                'points' => 10
            ],
            [
                'name' => 'Quick Learner',
                'description' => 'Complete 10 lessons',
                'icon' => '⚡',
                'requirement' => 'Complete 10 lessons',
                'points' => 50
            ],
            [
                'name' => 'Dedicated Student',
                'description' => 'Complete 50 lessons',
                'icon' => '📚',
                'requirement' => 'Complete 50 lessons',
                'points' => 200
            ],
            [
                'name' => 'Master Learner',
                'description' => 'Complete 100 lessons',
                'icon' => '🏆',
                'requirement' => 'Complete 100 lessons',
                'points' => 500
            ],
            [
                'name' => 'Perfect Score',
                'description' => 'Get 100% accuracy on a lesson',
                'icon' => '💯',
                'requirement' => 'Get 100% accuracy',
                'points' => 20
            ],
            [
                'name' => 'Consistent Learner',
                'description' => 'Practice for 7 days in a row',
                'icon' => '🔥',
                'requirement' => '7 day streak',
                'points' => 30
            ],
            [
                'name' => 'Month Champion',
                'description' => 'Practice for 30 days in a row',
                'icon' => '👑',
                'requirement' => '30 day streak',
                'points' => 100
            ],
            [
                'name' => 'Speed Demon',
                'description' => 'Complete a lesson in under 5 minutes',
                'icon' => '🚀',
                'requirement' => 'Complete lesson in < 5 mins',
                'points' => 25
            ],
            [
                'name' => 'Social Butterfly',
                'description' => 'Post 10 comments',
                'icon' => '💬',
                'requirement' => 'Post 10 comments',
                'points' => 15
            ],
            [
                'name' => 'Bookmark Collector',
                'description' => 'Bookmark 20 lessons',
                'icon' => '⭐',
                'requirement' => 'Bookmark 20 lessons',
                'points' => 20
            ]
        ];

        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
    }
}
