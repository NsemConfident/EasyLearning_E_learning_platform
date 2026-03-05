<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PastQuestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'title',
        'description',
        'subject',
        'level',
        'year',
        'category',
        'file_path',
        'file_size',
        'download_count',
        'is_published',
        'created_by',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function answers()
    {
        return $this->hasMany(PastQuestionAnswer::class);
    }

    protected static function booted(): void
    {
        static::creating(function (PastQuestion $pastQuestion) {
            if ($pastQuestion->created_by === null && auth()->check()) {
                $pastQuestion->created_by = auth()->id();
            }
        });

        static::saving(function (PastQuestion $pastQuestion) {
            if ($pastQuestion->file_path && Storage::disk('public')->exists($pastQuestion->file_path)) {
                $pastQuestion->file_size = Storage::disk('public')->size($pastQuestion->file_path);
            }
        });
    }
}
