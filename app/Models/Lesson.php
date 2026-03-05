<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    public const TYPE_VIDEO = 'video';
    public const TYPE_TEXT = 'text';

    protected $fillable = [
        'module_id',
        'title',
        'type',
        'content',
        'video_url',
        'video_path',
        'duration',
        'order',
    ];

    public function isVideo(): bool
    {
        return $this->type === self::TYPE_VIDEO;
    }

    public function isText(): bool
    {
        return $this->type === self::TYPE_TEXT;
    }

    /** URL or path for playback: uploaded file URL or external video_url */
    public function getVideoSourceAttribute(): ?string
    {
        if ($this->type !== self::TYPE_VIDEO) {
            return null;
        }
        if ($this->video_path) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->video_path);
        }
        return $this->video_url;
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function progresses()
    {
        return $this->hasMany(LessonUserProgress::class);
    }
}
