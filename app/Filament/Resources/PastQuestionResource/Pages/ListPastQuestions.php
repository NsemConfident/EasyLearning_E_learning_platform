<?php

namespace App\Filament\Resources\PastQuestionResource\Pages;

use App\Filament\Resources\PastQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPastQuestions extends ListRecords
{
    protected static string $resource = PastQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('directUpload')
                ->label('Add via direct upload')
                ->url(route('admin.past-questions.direct-upload.form'))
                ->color('gray')
                ->openUrlInNewTab(false),
            Actions\CreateAction::make(),
        ];
    }
}

