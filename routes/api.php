<?php

use App\Http\Controllers\Api\ProjectSyncController;
use Illuminate\Support\Facades\Route;
// API routes are automatically assigned the "api" middleware group and
// "/api" URL prefix via Laravel's routing configuration in bootstrap/app.php.
// Define endpoints here without wrapping them in another prefix group.

// Sync operations
Route::post('/sync/projects', [ProjectSyncController::class, 'syncProjects']);
Route::get('/sync/stats', [ProjectSyncController::class, 'stats']);

// Project operations
Route::get('/projects', [ProjectSyncController::class, 'index']);
Route::get('/projects/search', [ProjectSyncController::class, 'search']);
Route::get('/projects/{project}', [ProjectSyncController::class, 'show']);


