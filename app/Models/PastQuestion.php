<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PastQuestion extends Model
{
    use HasFactory;
    protected $fillable = [
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function booted(): void
    {
        static::saving(function (PastQuestion $pastQuestion) {
            if ($pastQuestion->file_path && Storage::disk('public')->exists($pastQuestion->file_path)) {
                $pastQuestion->file_size = Storage::disk('public')->size($pastQuestion->file_path);
            }
        });
    }
}
