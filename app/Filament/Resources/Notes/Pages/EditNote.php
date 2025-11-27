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
