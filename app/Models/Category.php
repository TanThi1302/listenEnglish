<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'image', 'order_index', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // QUAN TRỌNG: Phải có relationship này
    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('order_index');
    }

    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, Section::class);
    }
}