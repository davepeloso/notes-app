<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Filament\Forms\Components\ModalMarkdownEditor;
use App\Filament\Forms\Components\PhpEditor;
use App\Models\Tag;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class NotesRelationManager extends RelationManager
{
    protected static string $relationship = 'notes';

    protected static ?string $title = 'Notes';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            SchemaComponents\Section::make('Basic Information')
                ->schema([
                    \Filament\Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(2),

                    \Filament\Forms\Components\Select::make('type')
                        ->options([
                            'markdown' => 'Markdown',
                            'code' => 'PHP Code',
                            'mixed' => 'Mixed (Markdown + Code)',
                        ])
                        ->required()
                        ->default('markdown')
                        ->reactive()
                        ->columnSpan(1),
                ])
                ->columns(2),

            SchemaComponents\Section::make('Tags & Flags')
                ->schema([
                    \Filament\Forms\Components\Select::make('tags')
                        ->relationship('tags', 'name')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            \Filament\Forms\Components\TextInput::make('name')
                                ->required()
                                ->unique(Tag::class, 'name')
                                ->maxLength(255),
                            \Filament\Forms\Components\ColorPicker::make('color')
                                ->default('#10b981'),
                            \Filament\Forms\Components\Toggle::make('is_flag')
                                ->label('Is this a flag?')
                                ->helperText('Flags are special tags used for categorization'),
                        ])
                        ->columnSpan('full'),
                ])
                ->collapsible(),

            SchemaComponents\Section::make('Content')
                ->schema([
                    ModalMarkdownEditor::make('content')
                        ->label('Markdown')
                        ->visible(fn (Get $get) => in_array($get('type'), ['markdown', 'mixed'])),

                    PhpEditor::make('code_content')
                        ->label('PHP Code')
                        ->visible(fn (Get $get) => in_array($get('type'), ['code', 'mixed'])),
                ])
                ->columnSpan('full'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'markdown' => 'primary',
                        'code' => 'success',
                        'mixed' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                Tables\Columns\ViewColumn::make('tags')
                    ->view('filament.tables.columns.tag-badges'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->filters([])
            ->headerActions([
                Actions\CreateAction::make()
                    ->afterFormMounted(function () {
                        Filament::registerScripts([
                            'https://unpkg.com/easymde/dist/easymde.min.js',
                            'https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js',
                        ], true);
                        Filament::registerStyles([
                            'https://unpkg.com/easymde/dist/easymde.min.css',
                        ], true);
                    }),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->afterFormMounted(function () {
                        Filament::registerScripts([
                            'https://unpkg.com/easymde/dist/easymde.min.js',
                            'https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js',
                        ], true);
                        Filament::registerStyles([
                            'https://unpkg.com/easymde/dist/easymde.min.css',
                        ], true);
                    }),
                Actions\Action::make('code')
                    ->label('Code')
                    ->color('info')
                    ->url(fn ($record) => route('notes.monaco.edit', $record))
                    ->icon('heroicon-o-code-bracket')
                    ->openUrlInNewTab(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
            ]);
    }
}
