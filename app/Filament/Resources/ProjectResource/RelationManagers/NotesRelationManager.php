<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Models\Note;
use Filament\Actions;
use Filament\Forms\Components as FormComponents;
use Filament\Tables;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Facades\Filament;



class NotesRelationManager extends RelationManager
{
    protected static string $relationship = 'notes';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                FormComponents\TextInput::make('title')
                    ->required(),

                FormComponents\Textarea::make('content')
                    ->label('Content')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('updated_at')->dateTime(),
            ])
            ->headerActions([
                Actions\CreateAction::make(),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->defaultSort('updated_at', 'desc');
    }
}
