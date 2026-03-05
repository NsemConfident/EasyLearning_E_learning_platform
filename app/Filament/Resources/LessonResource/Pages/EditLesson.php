<?php

namespace App\Filament\Resources\LessonResource\Pages;

use App\Filament\Resources\LessonResource;
use App\Models\Lesson;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditLesson extends EditRecord
{
    protected static string $resource = LessonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['type'] ?? '') === Lesson::TYPE_VIDEO) {
            if (empty($data['video_path']) && empty($data['video_url'] ?? null)) {
                throw ValidationException::withMessages([
                    'video_path' => __('Either upload a video or enter an external video URL.'),
                ]);
            }
        }
        return $data;
    }
}

