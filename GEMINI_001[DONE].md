# 📋 Project Pages Installation Checklist

Use this checklist to ensure you've completed all steps correctly.


## Step 1 GEMINI : Copy Files

ALL FILES are in documents/upgrade/FOR GEMINI
- [x] Copy migration file to `database/migrations/`
- [x] Copy `ProjectPage.php` to `app/Models/`
- [x] Copy/update `Project.php` in `app/Models/`
- [x] Copy `ProjectPageController.php` to `app/Http/Controllers/`
- [x] Copy `ManageProjectPageAction.php` to `app/Filament/Actions/`
- [x] Copy `GenerateProjectPages.php` to `app/Console/Commands/`
- [x] Copy `show.blade.php` to `resources/views/projects/`
- [x] Copy `project-pages.php` to `routes/`


## Step 2 GEMINI: Run Migration

- [x] Run: `php artisan migrate`
- [x] Verify `project_pages` table exists in database
- [x] Check no errors in migration

**Verification:**
```bash
php artisan tinker
>>> \Schema::hasTable('project_pages')
# Should return: true
```

---

## Step 3 GEMINI: Register Routes

- [x] Open `routes/web.php`
- [x] Add: `require __DIR__ . '/project-pages.php';` at the bottom
- [x] Clear route cache: `php artisan route:clear`
- [x] Verify route exists: `php artisan route:list | grep project`

**You should see:**
```
GET  /project/{slug} › ProjectPageController@show
```

---

## Step 4 GEMINI: Update ProjectResource

- [x] Open `app/Filament/Resources/ProjectResource.php`
- [x] Add use statement: `use App\Filament\Actions\ManageProjectPageAction;`
- [x] Add actions to `table()` method (see EXAMPLE_ProjectResource.php)
- [x] Save file

**Verify in Code:**
```php
use App\Filament\Actions\ManageProjectPageAction;

public static function table(Table $table): Table
{
    return $table
        ->actions([
            ManageProjectPageAction::viewPageAction(),
            ManageProjectPageAction::make(),
            ManageProjectPageAction::deletePageAction(),
            Tables\Actions\EditAction::make(),
        ]);
}
```

---

## Step 5 GEMINI: Clear Caches

- [x] `php artisan config:clear`
- [x] `php artisan cache:clear`
- [x] `php artisan view:clear`
- [x] `php artisan route:clear`
- [x] `composer dump-autoload`


## Step 9 GEMINI: Generate Pages for All Projects

- [x] Run: `php artisan projects:generate-pages --missing`
- [x] Command completes successfully? ✅
- [x] Check output: "Created: X page(s)"
- [x] Refresh Filament projects list
- [x] All projects now have "View Page" button? ✅

---

## Step 10 DAVE (NOT GEMINI): Optional - Customize 

- [ ] Edit `resources/views/projects/show.blade.php` for custom styling
- [ ] Modify colors/gradients to match your brand
- [ ] Add additional fields to migration if needed
- [ ] Update page layout as desired

---

## Troubleshooting Checklist

### If "Class not found" errors:

- [ ] Run `composer dump-autoload`
- [ ] Check file paths are correct
- [ ] Verify namespace matches directory structure

### If routes don't work:

- [ ] Check `routes/web.php` includes project-pages.php
- [ ] Run `php artisan route:clear`
- [ ] Check `php artisan route:list | grep project`

### If actions don't show in Filament: DAVE (NOT GEMINI)

- [ ] Verify ProjectResource.php is updated
- [ ] Check use statement for ManageProjectPageAction
- [ ] Clear cache: `php artisan cache:clear`

### If styles don't load: DAVE (NOT GEMINI)

- [ ] Run `npm run build` or `npm run dev`
- [ ] Check Tailwind config includes views directory
- [ ] Hard refresh browser (Cmd+Shift+R / Ctrl+F5)

### If migration fails: 

- [ ] Check database connection
- [ ] Verify no table name conflicts
- [ ] Check migration filename is unique

---

## Post-Installation Verification

### Database Check

- [ ] `project_pages` table exists
- [ ] Has columns: id, project_id, slug, is_published, custom_content, meta_data, timestamps
- [ ] Foreign key constraint to projects table works

**Verify:**
```bash
php artisan tinker
>>> \App\Models\ProjectPage::count()
# Should return number > 0 after generating pages
```

### Route Check

- [ ] Route registered: `php artisan route:list | grep project`
- [ ] Returns: `GET /project/{slug}`

### Relationship Check

- [ ] Project has page relationship
- [ ] ProjectPage has project relationship

**Verify:**
```bash
php artisan tinker
>>> $project = \App\Models\Project::first()
>>> $project->page
# Should return ProjectPage instance or null
```

### Permission Check

- [ ] Published pages visible at `/project/{slug}`
- [ ] Unpublished pages return 404
- [ ] Admin can see all pages in Filament

---

## Final Tests

### Test Case 1 DAVE (NOT GEMINI): Create New Page

1. [ ] Go to any project in Filament
2. [ ] Click "Create Page"
3. [ ] Set custom slug
4. [ ] Set published = true
5. [ ] Save
6. [ ] Click "View Page"
7. [ ] Page loads successfully

### Test Case 2: Edit Existing Page

1. [ ] Click "Edit Page" on project with page
2. [ ] Change slug
3. [ ] Save
4. [ ] Old URL returns 404
5. [ ] New URL works

### Test Case 3: Toggle Published

1. [ ] Edit page
2. [ ] Set published = false
3. [ ] Save
4. [ ] Try visiting `/project/{slug}` - should get 404
5. [ ] Set published = true
6. [ ] Page now accessible

### Test Case 4: Delete Page

1. [ ] Click "Delete Page"
2. [ ] Confirm deletion
3. [ ] Page deleted
4. [ ] Project still exists
5. [ ] "Create Page" button appears again

### Test Case 5: Custom Content

1. [ ] Edit page
2. [ ] Add custom markdown in custom_content field
3. [ ] Save
4. [ ] View page
5. [ ] Custom content displays instead of project.content

---

## All Done! 🎉

If you've checked all boxes above, you're ready to go!

### Next Steps DAVE (NOT GEMINI)

- [ ] Customize the blade view styling
- [ ] Add more fields to project_pages if needed
- [ ] Create pages for all your projects
- [ ] Share your beautiful project pages!

---

## Quick Reference

**Create pages:**
```bash
php artisan projects:generate-pages --missing
```

**Check a page:**
```bash
php artisan tinker
>>> \App\Models\ProjectPage::first()
```

**List all pages:**
```bash
php artisan tinker
>>> \App\Models\ProjectPage::with('project')->get()
```

**Find page by slug:**
```bash
php artisan tinker
>>> \App\Models\ProjectPage::where('slug', 'your-slug')->first()
```

---

## Need Help?

Refer to:
- `README.md` - Overview and installation
- `PROJECT_PAGES_SETUP.md` - Detailed setup guide
- `QUICK_REFERENCE.md` - Command reference
- `ARCHITECTURE.md` - System architecture
- `EXAMPLE_ProjectResource.php` - Integration example

---

**Questions or issues?** Check the troubleshooting section above first!

**Everything working?** Awesome! Now go create some beautiful project pages! 🚀