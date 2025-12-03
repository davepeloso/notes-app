# Project Pages Architecture

## System Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                      Your Laravel App                           │
│                                                                 │
│  ┌──────────────────┐           ┌──────────────────┐          │
│  │   Projects       │           │  Project Pages   │          │
│  │   Table          │◄──────────│  Table (NEW)     │          │
│  │                  │  1-to-1   │                  │          │
│  │  • id            │           │  • id            │          │
│  │  • name          │           │  • project_id ──►│          │
│  │  • description   │           │  • slug          │          │
│  │  • content       │           │  • is_published  │          │
│  │  • context       │           │  • custom_content│          │
│  │  • timestamps    │           │  • timestamps    │          │
│  └──────────────────┘           └──────────────────┘          │
│         ▲                                 │                     │
│         │                                 │                     │
│         │                                 ▼                     │
│  ┌──────┴──────────┐           ┌──────────────────┐          │
│  │  Filament       │           │  Public Route    │          │
│  │  Admin          │           │  /project/{slug} │          │
│  │                 │           │                  │          │
│  │  Actions:       │           │  Controller:     │          │
│  │  • Create Page  │           │  • Load Page     │          │
│  │  • Edit Page    │           │  • Load Project  │          │
│  │  • View Page    │           │  • Render View   │          │
│  │  • Delete Page  │           │                  │          │
│  └─────────────────┘           └──────────────────┘          │
│                                          │                     │
│                                          ▼                     │
│                                 ┌──────────────────┐          │
│                                 │  Blade View      │          │
│                                 │  projects/show   │          │
│                                 │                  │          │
│                                 │  • Beautiful     │          │
│                                 │  • Responsive    │          │
│                                 │  • Animated      │          │
│                                 └──────────────────┘          │
└─────────────────────────────────────────────────────────────────┘
```

## Data Flow

### Creating a Page (Admin)

```
User clicks "Create Page" in Filament
           │
           ▼
    ManageProjectPageAction opens modal
           │
           ▼
    User sets slug, published status, custom content
           │
           ▼
    ProjectPage record created
           │
           ▼
    Links to Project via project_id
           │
           ▼
    Success notification shown
```

### Viewing a Page (Public)

```
User visits /project/{slug}
           │
           ▼
    ProjectPageController::show()
           │
           ▼
    Query: ProjectPage with Project and Tags
           │
           ▼
    Load content (custom or project.content)
           │
           ▼
    Render show.blade.php
           │
           ▼
    Beautiful page displayed!
```

## File Structure

```
your-laravel-app/
│
├── app/
│   ├── Models/
│   │   ├── Project.php (updated - added page() relationship)
│   │   └── ProjectPage.php (NEW)
│   │
│   ├── Http/Controllers/
│   │   └── ProjectPageController.php (NEW)
│   │
│   ├── Filament/
│   │   ├── Resources/
│   │   │   └── ProjectResource.php (update this - add actions)
│   │   └── Actions/
│   │       └── ManageProjectPageAction.php (NEW)
│   │
│   └── Console/Commands/
│       └── GenerateProjectPages.php (NEW)
│
├── database/migrations/
│   └── 2024_12_03_000001_create_project_pages_table.php (NEW)
│
├── resources/views/
│   └── projects/
│       └── show.blade.php (NEW)
│
└── routes/
    ├── web.php (update this - add require line)
    └── project-pages.php (NEW)
```

## Relationship Diagram

```
┌────────────────┐
│    Project     │
│                │
│    Methods:    │
│    • page()    │────┐
│    • tags()    │    │ hasOne
└────────────────┘    │
                      │
                      ▼
                ┌─────────────────┐
                │  ProjectPage    │
                │                 │
                │   Methods:      │
                │   • project()   │─── belongsTo
                │   • getUrl()    │
                │   • display()   │
                └─────────────────┘
```

## URL Routing

```
Admin URLs (Filament):
  /admin/projects              → List all projects
  /admin/projects/create       → Create new project
  /admin/projects/{id}/edit    → Edit project
  
Public URLs (New):
  /project/{slug}              → View project page
  
Examples:
  /project/photography-gallery
  /project/booking-system
  /project/my-laravel-app
```

## Safety Architecture

### What Changes?

```
❌ NOTHING in your projects table
❌ NO modifications to existing migrations
❌ NO breaking changes to current functionality

✅ New project_pages table (separate)
✅ New routes (doesn't affect existing)
✅ New actions in Filament (opt-in)
✅ New views (isolated)
```

### Rollback Path

```
php artisan migrate:rollback
           │
           ▼
    Drops project_pages table
           │
           ▼
    Everything back to normal!
    (Your projects table untouched)
```

## Security & Permissions

```
Project Pages Visibility:
                                    
┌─────────────────────────────┐
│   is_published = true       │──► Public can view at /project/{slug}
└─────────────────────────────┘

┌─────────────────────────────┐
│   is_published = false      │──► Only admins can see in Filament
└─────────────────────────────┘   (404 for public)
```

## Integration Points

### Your ProjectResource.php

```php
// BEFORE (your existing code)
->actions([
    Tables\Actions\EditAction::make(),
    Tables\Actions\DeleteAction::make(),
])

// AFTER (add project page actions)
->actions([
    ManageProjectPageAction::viewPageAction(),  // NEW
    ManageProjectPageAction::make(),            // NEW
    ManageProjectPageAction::deletePageAction(), // NEW
    Tables\Actions\EditAction::make(),
    Tables\Actions\DeleteAction::make(),
])
```

### Your routes/web.php

```php
// BEFORE (your existing routes)
Route::get('/', function () {
    return view('welcome');
});

// AFTER (add one line)
require __DIR__ . '/project-pages.php'; // NEW
```

That's it! Two small updates, zero breaking changes. 🎉

## Benefits of This Architecture

✅ **Separation of Concerns**
   - Projects table: Core project data
   - Project pages: Presentation layer

✅ **Flexible**
   - Add pages only where needed
   - Customize per-project
   - Easy to extend

✅ **Safe**
   - No migration dependencies
   - Easy rollback
   - Cascade deletes

✅ **Maintainable**
   - Clear relationships
   - Well-documented
   - Easy to test

✅ **Scalable**
   - Add more page features easily
   - Won't slow down project queries
   - Can add caching later
