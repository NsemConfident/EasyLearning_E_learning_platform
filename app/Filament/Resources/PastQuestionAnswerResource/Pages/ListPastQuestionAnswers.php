<?php

namespace App\Filament\Resources\PastQuestionAnswerResource\Pages;

use App\Filament\Resources\PastQuestionAnswerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPastQuestionAnswers extends ListRecords
{
    protected static string $resource = PastQuestionAnswerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
