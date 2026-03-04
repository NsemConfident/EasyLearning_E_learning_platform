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
            Actions\CreateAction::make(),
        ];
    }
}

