<?php

namespace App\Filament\Resources\Notes;

use App\Filament\Forms\Components\MarkdownEditor;
use App\Filament\Forms\Components\PhpEditor;
use App\Models\Note;
use App\Models\Tag;
use BackedEnum;
use Filament\Actions;
use Filament\Forms\Components as FormComponents;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use UnitEnum;

class NoteResource extends Resource
{
    protected static ?string $model = Note::class;

    protected static BackedEnum | string | null $navigationIcon = "heroicon-o-document-text";
    protected static string | UnitEnum | null $navigationGroup = "Notes";
    protected static ?string $label = "Note";

    protected static ?string $recordTitleAttribute = "title";

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            "Type" => ucfirst($record->type),
            "Project" => $record->project?->name ?? 'None',
            "Updated" => $record->updated_at->diffForHumans(),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ["title", "content", "code_content"];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            SchemaComponents\Section::make('Basic Information')
                ->schema([
                    FormComponents\TextInput::make("title")
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(2),

                    FormComponents\Select::make('type')
                        ->options([
                            'markdown' => 'Markdown',
                            'code' => 'PHP Code',
                            'mixed' => 'Mixed (Markdown + Code)',
                        ])
                        ->required()
                        ->default('markdown')
                        ->reactive()
                        ->columnSpan(1),

                    FormComponents\Select::make('project_id')
                        ->label('Project')
                        ->relationship('project', 'name')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            FormComponents\TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            FormComponents\Textarea::make('description')
                                ->maxLength(65535),
                            FormComponents\ColorPicker::make('color')
                                ->default('#3b82f6'),
                        ])
                        ->columnSpan(1),
                ])
                ->columns(2),

            SchemaComponents\Section::make('Tags & Flags')
                ->schema([
                    FormComponents\Select::make('tags')
                        ->relationship('tags', 'name')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            FormComponents\TextInput::make('name')
                                ->required()
                                ->unique(Tag::class, 'name')
                                ->maxLength(255),
                            FormComponents\ColorPicker::make('color')
                                ->default('#10b981'),
                            FormComponents\Toggle::make('is_flag')
                                ->label('Is this a flag?')
                                ->helperText('Flags are special tags used for categorization'),
                        ])
                        ->columnSpan('full'),
                ])
                ->collapsible(),

            SchemaComponents\Section::make('Content')
                ->schema([
                    MarkdownEditor::make("content")
                        ->label("Markdown")
                        ->visible(fn (Get $get) => in_array($get('type'), ['markdown', 'mixed'])),

                    PhpEditor::make("code_content")
                        ->label("PHP Code")
                        ->visible(fn (Get $get) => in_array($get('type'), ['code', 'mixed'])),
                ])
                ->columnSpan('full'),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("title")
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make("type")
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'markdown' => 'primary',
                        'code' => 'success',
                        'mixed' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                Tables\Columns\TextColumn::make("project.name")
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn ($record) => $record->project?->color ?? 'gray'),

                Tables\Columns\TextColumn::make("tags.name")
                    ->badge()
                    ->separator(',')
                    ->color(fn ($record, $state) => $record->tags->firstWhere('name', $state)?->color ?? 'gray')
                    ->limit(3),

                Tables\Columns\TextColumn::make("updated_at")
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'markdown' => 'Markdown',
                        'code' => 'PHP Code',
                        'mixed' => 'Mixed',
                    ]),
                Tables\Filters\SelectFilter::make('project')
                    ->relationship('project', 'name'),
                Tables\Filters\SelectFilter::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple(),
            ])
            ->actions([
                Actions\Action::make('edit')
                    ->url(fn ($record) => static::getUrl('edit', ['record' => $record]))
                    ->icon('heroicon-o-pencil'),
                Actions\Action::make('code')
                    ->label('Code')
                    ->color('info')
                    ->url(fn ($record) => route('notes.monaco.edit', $record))
                    ->icon('heroicon-o-code-bracket')
                    ->openUrlInNewTab(),
                Actions\Action::make('delete')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->delete())
                    ->icon('heroicon-o-trash')
                    ->color('danger'),
            ])
            ->bulkActions([
                Actions\BulkAction::make('delete')
                    ->requiresConfirmation()
                    ->action(fn ($records) => $records->each->delete())
                    ->icon('heroicon-o-trash')
                    ->color('danger'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListNotes::route("/"),
            "create" => Pages\CreateNote::route("/create"),
            "edit" => Pages\EditNote::route("/{record}/edit"),
        ];
    }
}
