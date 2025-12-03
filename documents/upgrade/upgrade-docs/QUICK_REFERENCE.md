# Project Pages - Quick Reference

## 📁 Files Created

```
database/migrations/
  └─ 2024_12_03_000001_create_project_pages_table.php

app/Models/
  ├─ ProjectPage.php
  └─ Project.php (with page relationship)

app/Http/Controllers/
  └─ ProjectPageController.php

app/Filament/Actions/
  └─ ManageProjectPageAction.php

app/Console/Commands/
  └─ GenerateProjectPages.php

resources/views/projects/
  └─ show.blade.php

routes/
  └─ project-pages.php
```

## 🔗 URLs

```
Admin Area:
  /admin/projects          → List all projects
  /admin/projects/{id}     → Edit project (existing)

Public Pages:
  /project/{slug}          → View project page
  
Example:
  /project/photography-gallery
  /project/client-booking-system
  /project/my-awesome-project
```

## ⚡ Quick Commands

```bash
# Run migration
php artisan migrate

# Generate pages for all projects without one
php artisan projects:generate-pages --missing

# Generate pages for ALL projects
php artisan projects:generate-pages --all

# Create pages as drafts (unpublished)
php artisan projects:generate-pages --missing --unpublish

# Rollback (if needed)
php artisan migrate:rollback
```

## 🎯 Integration Checklist

- [ ] Run migration: `php artisan migrate`
- [ ] Add route: Update `routes/web.php` to include `project-pages.php`
- [ ] Update ProjectResource: Add project page actions (see EXAMPLE_ProjectResource.php)
- [ ] Generate pages: `php artisan projects:generate-pages --missing`
- [ ] Test: Visit `/project/{slug}` in browser
- [ ] Customize: Edit `resources/views/projects/show.blade.php` for styling

## 🔍 How to Use

### In Filament Admin

1. Go to Projects list
2. On any row, click the **"Page"** button
3. Choose:
   - **View Page** - Opens public page (if published)
   - **Create Page** - Set up new page
   - **Edit Page** - Modify existing page
   - **Delete Page** - Remove page (keeps project)

### Creating a Page

1. Click "Create Page" on a project
2. Confirm/edit the slug (URL)
3. Toggle published status
4. Add optional custom content
5. Save!

### Viewing a Page

Click "View Page" button or visit:
```
https://yourapp.test/project/{slug}
```

## 📊 Database Relationships

```
Project (id=1, name="My Project")
  └─ hasOne ProjectPage (id=1, slug="my-project", is_published=true)

ProjectPage (id=1, project_id=1)
  └─ belongsTo Project (id=1)
```

## 🎨 Customization Points

### Change URL Pattern
In `routes/project-pages.php`:
```php
Route::get('/projects/{slug}', ...)  // Changed from /project/{slug}
```

### Add Custom Fields
In migration:
```php
$table->string('hero_image')->nullable();
$table->text('excerpt')->nullable();
```

### Modify Page Design
Edit `resources/views/projects/show.blade.php`

### Change Slug Generation
In `ProjectPage::generateSlug()` method

## 🚨 Important Notes

✅ Your `projects` table is **NEVER modified**
✅ Safe to rollback - just `php artisan migrate:rollback`
✅ Deleting a project automatically deletes its page (cascade)
✅ Deleting a page does NOT delete the project
✅ Slugs must be unique across all pages

## 🐛 Common Issues

**Routes not working?**
→ Add `require __DIR__ . '/project-pages.php';` to `web.php`

**Actions not showing in Filament?**
→ Update your `ProjectResource::table()` method

**"Class not found"?**
→ Run `composer dump-autoload`

**Styles not loading?**
→ Run `npm run build` or `npm run dev`

---

## Example Flow

```
1. User creates project in Filament
   ↓
2. Admin clicks "Create Page"
   ↓
3. Page created with auto-generated slug
   ↓
4. User clicks "View Page"
   ↓
5. Beautiful project page opens at /project/{slug}
```

## Quick Test

```bash
# 1. Migrate
php artisan migrate

# 2. Generate pages
php artisan projects:generate-pages --missing

# 3. Visit in browser
open http://yourapp.test/project/your-first-project
```

That's it! 🎉
