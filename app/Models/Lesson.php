<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'section_id', 'title', 'slug', 'description', 'difficulty_level',
        'total_parts', 'order_index', 'is_premium'
    ];

    protected $casts = [
        'is_premium' => 'boolean',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    // ← QUAN TRỌNG: THÊM RELATIONSHIP NÀY
    public function parts()
    {
        return $this->hasMany(LessonPart::class)->orderBy('part_number');
    }

    public function vocabulary()
    {
        return $this->belongsToMany(Vocabulary::class, 'lesson_vocabulary');
    }

    public function userProgress()
    {
        return $this->hasMany(UserProgress::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    public function isBookmarkedBy($userId)
    {
        return $this->bookmarks()->where('user_id', $userId)->exists();
    }

    public function getUserProgress($userId)
    {
        return $this->userProgress()->where('user_id', $userId)->first();
    }
}