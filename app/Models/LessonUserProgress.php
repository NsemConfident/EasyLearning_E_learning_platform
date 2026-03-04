<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonUserProgress extends Model
{
    protected $table = 'lesson_user_progress';

    protected $fillable = [
        'user_id',
        'lesson_id',
        'completed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
