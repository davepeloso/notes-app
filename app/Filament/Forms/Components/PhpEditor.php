<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class PhpEditor extends Field
{
    protected string $view = 'filament.forms.components.php-editor';

    protected function setUp(): void
    {
        parent::setUp();

        $this->dehydrateStateUsing(fn ($state) => $state);
    }
}
