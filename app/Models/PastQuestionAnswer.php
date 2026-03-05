<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class PastQuestionAnswer extends Model
{
    use HasFactory;

    protected $table = 'past_question_answers';

    protected $fillable = [
        'past_question_id',
        'file_path',
        'file_size',
    ];

    public function pastQuestion()
    {
        return $this->belongsTo(PastQuestion::class);
    }

    protected static function booted(): void
    {
        static::saving(function (PastQuestionAnswer $answer) {
            if ($answer->file_path && Storage::disk('public')->exists($answer->file_path)) {
                $answer->file_size = Storage::disk('public')->size($answer->file_path);
            }
        });
    }
}
