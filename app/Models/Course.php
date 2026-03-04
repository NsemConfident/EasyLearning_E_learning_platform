<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'price',
        'instructor_name',
        'is_published',
    ];

    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
