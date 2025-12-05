<?php

namespace App\Filament\Resources\Notes\Pages;

use App\Filament\Resources\Notes\NoteResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNote extends EditRecord
{
    protected static string $resource = NoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save')
                ->color('success')
                ->action('save'),

            Action::make('projectPage')
                ->label('Project Page')
                ->color('primary')
                ->icon('heroicon-o-link')
                ->url(fn () => $this->record?->project?->page?->url)
                ->openUrlInNewTab()
                ->visible(fn () => $this->record && $this->record->project && $this->record->project->page && $this->record->project->page->is_published),

            Action::make('code')
                ->label('Code')
                ->color('info')
                ->url(fn () => route('notes.monaco.edit', $this->record))
                ->openUrlInNewTab(),

            DeleteAction::make()
                ->color('danger'),
        ];
    }
}
