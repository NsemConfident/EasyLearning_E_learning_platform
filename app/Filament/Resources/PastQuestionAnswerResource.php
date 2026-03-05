<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PastQuestionAnswerResource\Pages;
use App\Models\PastQuestion;
use App\Models\PastQuestionAnswer;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;
use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;

class PastQuestionAnswerResource extends Resource
{
    protected static ?string $model = PastQuestionAnswer::class;

    protected static ?int $navigationSort = 2;

    public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        return 'heroicon-o-document-check';
    }

    public static function getModelLabel(): string
    {
        return 'Past Question Answer';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Past Question Answers';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('past_question_id')
                    ->label('Past Question')
                    ->options(fn () => PastQuestion::query()->orderBy('title')->pluck('title', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\FileUpload::make('file_path')
                    ->label('Answer PDF')
                    ->disk('public')
                    ->directory('past-question-answers')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(20480)
                    ->required()
                    ->downloadable()
                    ->helperText('Max 20 MB. PDF only.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('pastQuestion.title')
                    ->label('Past Question')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('file_size')
                    ->label('File size (KB)')
                    ->formatStateUsing(fn ($state) => $state ? round($state / 1024, 1) : 0),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPastQuestionAnswers::route('/'),
            'create' => Pages\CreatePastQuestionAnswer::route('/create'),
            'edit' => Pages\EditPastQuestionAnswer::route('/{record}/edit'),
        ];
    }
}
