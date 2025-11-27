<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    protected $fillable = [
        'user_id',
        'lesson_part_id',
        'user_answer',
        'is_correct',
        'mistakes_count',
        'time_taken',
        'hints_used'
    ];
}