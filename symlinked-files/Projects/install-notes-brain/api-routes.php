<?php

use App\Http\Controllers\Api\ProjectSyncController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes for Project Analyzer Integration
|--------------------------------------------------------------------------
|
| These routes handle syncing analyzed projects to the Notes.test database
|
*/

// Public routes (if you want to allow unauthenticated access)
// For production, you should add authentication middleware

Route::prefix('api')->group(function () {
    
    // Sync operations
    Route::post('/sync/projects', [ProjectSyncController::class, 'syncProjects'])
        ->name('api.sync.projects');
    
    Route::get('/sync/stats', [ProjectSyncController::class, 'stats'])
        ->name('api.sync.stats');
    
    // Project operations
    Route::get('/projects', [ProjectSyncController::class, 'index'])
        ->name('api.projects.index');
    
    Route::get('/projects/search', [ProjectSyncController::class, 'search'])
        ->name('api.projects.search');
    
    Route::get('/projects/{project}', [ProjectSyncController::class, 'show'])
        ->name('api.projects.show');
});

// If you want to add authentication, use this instead:
/*
Route::prefix('api')->middleware(['auth:sanctum'])->group(function () {
    // ... all the routes above ...
});
*/
