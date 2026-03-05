<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PastQuestionResource\Pages;
use App\Models\PastQuestion;
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

class PastQuestionResource extends Resource
{
    protected static ?string $model = PastQuestion::class;

    public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        return 'heroicon-o-document-text';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name', modifyQueryUsing: fn ($query) => $query->forPastQuestions())
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->rows(3),
                Forms\Components\TextInput::make('subject')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('level')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('year')
                    ->required()
                    ->maxLength(10),
                Forms\Components\FileUpload::make('file_path')
                    ->label('PDF File')
                    ->disk('public')
                    ->directory('past-questions')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(20480)
                    ->required()
                    ->downloadable(),
                Forms\Components\Toggle::make('is_published')
                    ->label('Published'),
                Forms\Components\TextInput::make('download_count')
                    ->disabled()
                    ->label('Download count'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('category.name')->label('Category')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('subject')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('level')->sortable(),
                Tables\Columns\TextColumn::make('year')->sortable(),
                Tables\Columns\IconColumn::make('is_published')->boolean()->label('Published'),
                Tables\Columns\TextColumn::make('file_size')
                    ->label('File size (KB)')
                    ->formatStateUsing(fn ($state) => $state ? round($state / 1024, 1) : 0),
                Tables\Columns\TextColumn::make('download_count')->label('Downloads'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
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
            'index' => Pages\ListPastQuestions::route('/'),
            'create' => Pages\CreatePastQuestion::route('/create'),
            'edit' => Pages\EditPastQuestion::route('/{record}/edit'),
        ];
    }
}

