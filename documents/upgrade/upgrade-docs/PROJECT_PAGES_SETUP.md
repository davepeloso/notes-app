# Project Pages Setup Guide

A safe, non-destructive way to add beautiful project detail pages to your Filament app.

## ✅ What Was Created

- ✨ `project_pages` table - separate from your existing `projects` table
- 📄 `ProjectPage` model with slug generation
- 🎨 Beautiful blade template inspired by your Next.js design
- 🔗 Routes for public project pages
- ⚡ Artisan command to generate pages
- 🎯 Filament actions to manage pages from admin

## 🚀 Installation Steps

### 1. Run the Migration

```bash
php artisan migrate
```

This creates the `project_pages` table (doesn't touch your existing `projects` table).

### 2. Include the Route File

In `routes/web.php`, add this line:

```php
// Add at the bottom of routes/web.php
require __DIR__ . '/project-pages.php';
```

Or manually add the route:

```php
use App\Http\Controllers\ProjectPageController;

Route::get('/project/{slug}', [ProjectPageController::class, 'show'])
    ->name('project.show');
```

### 3. Update Your Project Filament Resource

In `app/Filament/Resources/ProjectResource.php`, add the actions to your table:

```php
use App\Filament\Actions\ManageProjectPageAction;
use Filament\Tables\Table;

public static function table(Table $table): Table
{
    return $table
        ->columns([
            // ... your existing columns
        ])
        ->actions([
            // Add these new actions:
            ManageProjectPageAction::viewPageAction(),
            ManageProjectPageAction::make(),
            ManageProjectPageAction::deletePageAction(),
            
            // ... your existing actions
            Tables\Actions\EditAction::make(),
        ]);
}
```

### 4. Generate Pages for Existing Projects

Run this command to create pages for all your existing projects:

```bash
# Create pages for all projects that don't have one
php artisan projects:generate-pages --missing

# Or create pages for ALL projects
php artisan projects:generate-pages --all

# Create as unpublished (draft mode)
php artisan projects:generate-pages --missing --unpublish
```

## 🎨 How It Works

### Database Structure

```
projects (your existing table - untouched!)
  â"œâ"€ id
  â"œâ"€ name
  â"œâ"€ description
  â""â"€ content

project_pages (new table)
  â"œâ"€ id
  â"œâ"€ project_id (foreign key → projects.id)
  â"œâ"€ slug (unique, for URL)
  â"œâ"€ is_published (show/hide page)
  â""â"€ custom_content (optional override)
```

### URL Structure

Projects are accessible at:
```
/project/my-project-slug
```

The slug is auto-generated from the project name (e.g., "My Amazing Project" → "my-amazing-project").

## 📝 Usage

### From Filament Admin

On any project row, you'll see action buttons:

1. **Create Page** (if no page exists)
   - Sets up a page with auto-generated slug
   - Configure publish status
   - Add optional custom content

2. **View Page** (if page exists and is published)
   - Opens the public page in a new tab

3. **Edit Page** (if page exists)
   - Change slug
   - Toggle published status
   - Update custom content

4. **Delete Page**
   - Removes the project page (doesn't delete the project!)

### From Code

```php
use App\Models\Project;
use App\Models\ProjectPage;

// Create a page for a project
$project = Project::find(1);
$page = ProjectPage::create([
    'project_id' => $project->id,
    'slug' => 'my-custom-slug',
    'is_published' => true,
]);

// Check if project has a page
if ($project->hasPublishedPage()) {
    $url = $project->page_url;
}

// Get page URL
$pageUrl = route('project.show', $page->slug);
```

## 🎯 Features

### Auto-Generated Content

The page displays:
- Project name and description
- All associated tags (with colors)
- Project metadata (ID, context, dates)
- Content from `projects.content` field
- Markdown rendering
- Beautiful gradient design

### Custom Content Override

If you set `custom_content` on a project page, it displays that instead of the project's default content. Great for adding extra documentation!

### Publish/Draft Mode

Toggle `is_published` to hide pages from public view while you're working on them.

## 🎨 Customization

### Styling

The blade view is in `resources/views/projects/show.blade.php`. Customize:
- Colors and gradients
- Typography
- Layout
- Animations

### Adding More Fields

Want to add more data? Update the migration:

```php
// In project_pages migration:
$table->string('hero_image')->nullable();
$table->text('excerpt')->nullable();
$table->json('custom_sections')->nullable();
```

Then update the model and view accordingly.

## 🔒 Safety Features

✅ **Non-destructive** - doesn't modify your `projects` table
✅ **Cascade delete** - if a project is deleted, its page goes too
✅ **Unique slugs** - prevents URL conflicts
✅ **Draft mode** - publish when ready
✅ **Easy rollback** - just drop the `project_pages` table if needed

## 🐛 Troubleshooting

### "Class not found" errors

Make sure you've created all the files and run:
```bash
composer dump-autoload
```

### Route not working

Ensure you've added the route file to `web.php` or registered the route manually.

### Styles not loading

Run:
```bash
npm run build
```

### Can't see actions in Filament

Make sure you've updated your `ProjectResource.php` with the new actions.

## 📚 Example Workflow

```bash
# 1. Run migration
php artisan migrate

# 2. Generate pages for all existing projects
php artisan projects:generate-pages --missing

# 3. Visit Filament admin
# 4. Click "View Page" on any project
# 5. See your beautiful project page!
```

## 🚀 Next Steps

- Customize the blade template design
- Add more metadata fields
- Create a public "All Projects" index page
- Add sharing/export features
- Integrate with your photography workflow!

## Need Help?

- Check the model relationships in `Project.php` and `ProjectPage.php`
- Review the controller logic in `ProjectPageController.php`
- Inspect the Filament actions in `ManageProjectPageAction.php`

---

**That's it!** Your existing projects table is completely safe, and you have beautiful project detail pages. 🎉
