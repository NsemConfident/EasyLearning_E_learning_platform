<?php

namespace App\Filament\Resources\PastQuestionResource\Pages;

use App\Filament\Resources\PastQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPastQuestion extends EditRecord
{
    protected static string $resource = PastQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

