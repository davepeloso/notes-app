<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class ModalMarkdownEditor extends Field
{
    protected string $view = 'filament.forms.components.modal-markdown-editor';

    protected function setUp(): void
    {
        parent::setUp();

        $this->dehydrateStateUsing(fn ($state) => $state);
    }
}
