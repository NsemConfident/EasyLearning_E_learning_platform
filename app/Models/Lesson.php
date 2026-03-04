<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'module_id',
        'title',
        'video_url',
        'duration',
        'order',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function progresses()
    {
        return $this->hasMany(LessonUserProgress::class);
    }
}
