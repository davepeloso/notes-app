<?php

namespace App\Filament\Resources;

use App\Models\Tag;
use BackedEnum;
use Filament\Actions;
use Filament\Forms\Components as FormComponents;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use UnitEnum;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static BackedEnum | string | null $navigationIcon = 'heroicon-o-tag';
    protected static UnitEnum | string | null $navigationGroup = 'Organization';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            FormComponents\TextInput::make('name')
                ->required()
                ->unique(Tag::class, 'name', ignoreRecord: true)
                ->maxLength(255),

            FormComponents\ColorPicker::make('color')
                ->default('#10b981'),

            FormComponents\Toggle::make('is_flag')
                ->label('Is Flag')
                ->helperText('Flags are special tags used for categorization')
                ->default(false),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\ColorColumn::make('color'),

                Tables\Columns\IconColumn::make('is_flag')
                    ->boolean()
                    ->label('Flag'),

                Tables\Columns\TextColumn::make('notes_count')
                    ->counts('notes')
                    ->label('Notes'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_flag')
                    ->label('Flags Only')
                    ->placeholder('All tags')
                    ->trueLabel('Flags only')
                    ->falseLabel('Regular tags only'),
            ])
            ->actions([
                Actions\Action::make('edit')
                    ->url(fn ($record) => static::getUrl('edit', ['record' => $record]))
                    ->icon('heroicon-o-pencil'),
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
            'index' => Pages\TagResource\ListTags::route('/'),
            'create' => Pages\TagResource\CreateTag::route('/create'),
            'edit' => Pages\TagResource\EditTag::route('/{record}/edit'),
        ];
    }
}
