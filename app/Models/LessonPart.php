<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonPart extends Model
{
    protected $fillable = [
        'lesson_id', 'part_number', 'text_content', 'audio_file',
        'audio_duration', 'hints', 'explanation'
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }
}