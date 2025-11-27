<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    protected $fillable = [
        'word', 'pronunciation', 'meaning', 'example_sentence', 'difficulty_level'
    ];

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_vocabulary');
    }
}
