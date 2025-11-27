<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class MarkdownEditor extends Field
{
    protected string $view = 'filament.forms.components.markdown-editor';

    protected function setUp(): void
    {
        parent::setUp();

        $this->dehydrateStateUsing(fn ($state) => $state);
    }
}
