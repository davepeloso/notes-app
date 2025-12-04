<?php

namespace App\Filament\Resources;

use App\Filament\Actions\ManageProjectPageAction;
use App\Models\Project;
use BackedEnum;
use Filament\Actions;
use Filament\Forms\Components as FormComponents;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use App\Filament\Resources\ProjectResource\RelationManagers\NotesRelationManager;
use UnitEnum;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static BackedEnum | string | null $navigationIcon = 'heroicon-o-folder';
    protected static UnitEnum | string | null $navigationGroup = 'Organization';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            FormComponents\TextInput::make('name')
                ->required()
                ->maxLength(255),

            FormComponents\Textarea::make('description')
                ->maxLength(65535)
                ->rows(3),

            FormComponents\ColorPicker::make('color')
                ->default('#3b82f6'),
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

                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\ColorColumn::make('color'),

                Tables\Columns\TextColumn::make('notes_count')
                    ->counts('notes')
                    ->label('Notes'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->filters([])
            ->actions([
                ManageProjectPageAction::viewPageAction(),
                ManageProjectPageAction::make(),
                ManageProjectPageAction::deletePageAction(),
                Actions\EditAction::make(),
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
            'index' => Pages\ProjectResource\ListProjects::route('/'),
            'create' => Pages\ProjectResource\CreateProject::route('/create'),
            'edit' => Pages\ProjectResource\EditProject::route('/{record}/edit'),
        ];
    }

    /**
     * Register relation managers displayed on the Project edit page.
     */
    public static function getRelations(): array
    {
        return [
            NotesRelationManager::class,
        ];
    }
}
