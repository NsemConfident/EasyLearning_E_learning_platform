<?php

namespace App\Filament\Resources\LessonResource\Pages;

use App\Filament\Resources\LessonResource;
use App\Models\Lesson;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateLesson extends CreateRecord
{
    protected static string $resource = LessonResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (($data['type'] ?? '') === Lesson::TYPE_VIDEO) {
            if (empty($data['video_path']) && empty($data['video_url'] ?? null)) {
                throw ValidationException::withMessages([
                    'video_path' => 'Either upload a video or enter an external video URL.',
                ]);
            }
        }
        return $data;
    }
}

