<?php

use App\Http\Controllers\FontController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MonacoNoteController;
use Illuminate\Support\Facades\Route;

Route::get("/", [HomeController::class, "index"]);

// Font preview page
Route::get('/fonts', [FontController::class, 'index'])->name('fonts.index');

// Prevent favicon 404 noise in console when no favicon file is present
Route::get('/favicon.ico', function () {
    return response()->noContent();
});

// Standalone Monaco editor for note code_content (non-Livewire pop-out)
Route::get('/notes/{note}/monaco', [MonacoNoteController::class, 'edit'])
    ->name('notes.monaco.edit');

Route::post('/notes/{note}/monaco', [MonacoNoteController::class, 'update'])
    ->name('notes.monaco.update');
