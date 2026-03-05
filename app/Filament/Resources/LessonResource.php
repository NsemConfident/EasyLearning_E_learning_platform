<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LessonResource\Pages;
use App\Models\Lesson;
use Filament\Forms;
use Filament\Schemas\Components\Utilities\Get;
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

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        return 'heroicon-o-play-circle';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('module_id')
                    ->relationship('module', 'title')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->label('Lesson type')
                    ->options([
                        Lesson::TYPE_VIDEO => 'Video',
                        Lesson::TYPE_TEXT => 'Text',
                    ])
                    ->default(Lesson::TYPE_VIDEO)
                    ->required()
                    ->live(),
                // Video: upload or external URL
                Forms\Components\FileUpload::make('video_path')
                    ->label('Upload video')
                    ->disk('public')
                    ->directory('lesson-videos')
                    ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime'])
                    ->maxSize(512000) // 500 MB in KB
                    ->nullable()
                    ->visible(fn (Get $get) => $get('type') === Lesson::TYPE_VIDEO)
                    ->helperText('Upload a video file (MP4, WebM, OGG). Or use external URL below.'),
                Forms\Components\TextInput::make('video_url')
                    ->label('External video URL')
                    ->url()
                    ->maxLength(500)
                    ->nullable()
                    ->visible(fn (Get $get) => $get('type') === Lesson::TYPE_VIDEO)
                    ->helperText('Optional: link to external video (e.g. YouTube, Vimeo) if not uploading.'),
                Forms\Components\TextInput::make('duration')
                    ->numeric()
                    ->helperText('Duration in seconds (for video)')
                    ->nullable()
                    ->visible(fn (Get $get) => $get('type') === Lesson::TYPE_VIDEO),
                // Text: rich editor
                Forms\Components\RichEditor::make('content')
                    ->label('Lesson content')
                    ->required(fn (Get $get) => $get('type') === Lesson::TYPE_TEXT)
                    ->nullable()
                    ->visible(fn (Get $get) => $get('type') === Lesson::TYPE_TEXT)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('module.title')->label('Module')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('type')->badge()->color(fn (string $state) => $state === Lesson::TYPE_VIDEO ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('duration')->label('Duration (s)')->placeholder('—'),
                Tables\Columns\TextColumn::make('order')->sortable(),
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
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
        ];
    }
}

