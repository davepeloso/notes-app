<?php

namespace App\Filament\Widgets;

use App\Models\Note;
use Illuminate\Support\Str;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentNotesWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Note::query()
                    ->with(['project', 'tags'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(60)
                    ->description(fn (Note $record): string => Str::limit(strip_tags((string) $record->content), 120))
                    ->wrap()
                    ->url(fn (Note $record): string => route('filament.admin.resources.notes.edit', $record)),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'markdown' => 'primary',
                        'code' => 'success',
                        'mixed' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                Tables\Columns\TextColumn::make('project.name')
                    ->label('Project')
                    ->badge()
                    ->color(fn (Note $record) => $record->project?->color ?? 'gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tags.name')
                    ->label('Tags')
                    ->badge()
                    ->separator(',')
                    ->color(fn (Note $record, string $state) => $record->tags->firstWhere('name', $state)?->color ?? 'gray')
                    ->limit(3),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->recordActions([
                Actions\Action::make('code')
                    ->label('Code')
                    ->icon('heroicon-o-code-bracket')
                    ->color('info')
                    ->url(fn (Note $record): string => route('notes.monaco.edit', $record))
                    ->openUrlInNewTab(),
            ])
            ->paginated(false)
            ->heading('Recent Notes');
    }
}
