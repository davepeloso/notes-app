<?php

namespace App\Filament\Actions;

use App\Models\ProjectPage;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;

class ManageProjectPageAction
{
    /**
     * Create or edit project page action
     */
    public static function make(): Action
    {
        return Action::make('managePage')
            ->label(fn ($record) => $record->page ? 'Edit Page' : 'Create Page')
            ->icon(fn ($record) => $record->page ? 'heroicon-o-pencil-square' : 'heroicon-o-plus-circle')
            ->color(fn ($record) => $record->page ? 'warning' : 'success')
            ->form(function ($record) {
                $page = $record->page;

                return [
                    TextInput::make('slug')
                        ->label('Page URL Slug')
                        ->required()
                        ->default($page?->slug ?? ProjectPage::generateSlug($record->name))
                        ->helperText('URL-friendly identifier (e.g., my-project-name)')
                        ->rules(['alpha_dash', 'max:255']),

                    Toggle::make('is_published')
                        ->label('Published')
                        ->default($page?->is_published ?? true)
                        ->helperText('Toggle to show/hide this project page publicly'),

                    Textarea::make('custom_content')
                        ->label('Custom Content (Optional)')
                        ->default($page?->custom_content)
                        ->helperText('Override the README/description with custom markdown')
                        ->rows(12),
                ];
            })
            ->action(function ($record, array $data) {
                // Check if slug is unique (excluding current page if editing)
                $slugExists = ProjectPage::where('slug', $data['slug'])
                    ->when($record->page, fn($q) => $q->where('id', '!=', $record->page->id))
                    ->exists();

                if ($slugExists) {
                    Notification::make()
                        ->title('Slug already exists')
                        ->danger()
                        ->body('Please choose a different URL slug.')
                        ->send();

                    return;
                }

                // Create or update
                if ($record->page) {
                    $record->page->update($data);
                    $action = 'updated';
                } else {
                    ProjectPage::create([
                        'project_id' => $record->id,
                        'slug' => $data['slug'],
                        'is_published' => $data['is_published'],
                        'custom_content' => $data['custom_content'],
                    ]);
                    $action = 'created';
                }

                Notification::make()
                    ->title("Project page {$action} successfully")
                    ->success()
                    ->send();
            })
            ->modalHeading(fn ($record) => $record->page ? 'Edit Project Page' : 'Create Project Page')
            ->modalWidth('4xl');
    }

    /**
     * View project page action
     */
    public static function viewPageAction(): Action
    {
        return Action::make('viewPage')
            ->label('View Page')
            ->icon('heroicon-o-eye')
            ->color('primary')
            ->url(fn ($record) => $record->page?->url)
            ->openUrlInNewTab()
            ->visible(fn ($record) => $record->page && $record->page->is_published);
    }

    /**
     * Delete project page action
     */
    public static function deletePageAction(): Action
    {
        return Action::make('deletePage')
            ->label('Delete Page')
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->action(function ($record) {
                $record->page()->delete();

                Notification::make()
                    ->title('Project page deleted')
                    ->success()
                    ->send();
            })
            ->visible(fn ($record) => $record->page !== null);
    }
}
