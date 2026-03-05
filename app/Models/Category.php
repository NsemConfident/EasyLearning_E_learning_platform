<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    public const TYPE_COURSE = 'course';

    public const TYPE_PAST_QUESTION = 'past_question';

    public const TYPES = [
        self::TYPE_COURSE => 'Courses',
        self::TYPE_PAST_QUESTION => 'Past Questions',
    ];

    protected $fillable = [
        'type',
        'name',
        'slug',
        'description',
    ];

    protected static function booted(): void
    {
        static::saving(function (Category $category) {
            if (blank($category->slug) && filled($category->name)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function scopeForCourses(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_COURSE);
    }

    public function scopeForPastQuestions(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_PAST_QUESTION);
    }

    public function isForCourses(): bool
    {
        return $this->type === self::TYPE_COURSE;
    }

    public function isForPastQuestions(): bool
    {
        return $this->type === self::TYPE_PAST_QUESTION;
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function pastQuestions()
    {
        return $this->hasMany(PastQuestion::class);
    }
}
