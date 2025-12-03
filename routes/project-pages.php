<?php

use App\Http\Controllers\ProjectPageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Project Page Routes
|--------------------------------------------------------------------------
|
| Public-facing project pages accessible from Filament admin
|
*/

Route::get('/project/{slug}', [ProjectPageController::class, 'show'])
    ->name('project.show');
