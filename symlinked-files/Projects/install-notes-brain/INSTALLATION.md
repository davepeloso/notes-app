# Project Analyzer → Notes.test Integration

## Complete Installation Guide

This guide will walk you through integrating the Project Analyzer with your Notes.test Laravel application.

---

## 📋 Prerequisites

- Project Organizer installed and working
- Notes.test Laravel app running
- Python 3.8+
- Composer
- PHP 8.2+

---

## 🚀 Quick Install

```bash
# Navigate to Notes.test Laravel app
cd /Users/davepeloso/Herd/notes

# 1. Add the API Controller
mkdir -p app/Http/Controllers/Api
cp ProjectSyncController.php app/Http/Controllers/Api/

# 2. Add API routes
cat /path/to/api-routes.php >> routes/api.php

# 3. Test the API
php artisan route:list --path=api

# 4. Install enhanced analyzer
cp /path/to/project-organizer-ai.py ~/.local/bin/
cp /path/to/sync-to-notes.py ~/.local/bin/
chmod +x ~/.local/bin/project-organizer-ai.py
chmod +x ~/.local/bin/sync-to-notes.py

# 5. Create symlinks
cd ~/.local/bin
ln -sf project-organizer-ai.py project-organizer-ai
ln -sf sync-to-notes.py sync-to-notes
```

---

## 📁 File Locations

### Laravel (Notes.test)

```
/Users/davepeloso/Herd/notes/
├── app/
│   └── Http/
│       └── Controllers/
│           └── Api/
│               └── ProjectSyncController.php  ← NEW
├── routes/
│   └── api.php  ← UPDATE
└── ...
```

### Project Organizer

```
~/.local/bin/
├── project-organizer.sh
├── project-organizer-ai.py  ← REPLACE with enhanced version
├── sync-to-notes.py  ← NEW
└── ...

~/.project_data/
├── projects.json              # Scanner output
├── organization_proposal.md   # Human-readable report
└── notes_sync_data.json      # NEW - API-ready data
```

---

## 🔧 Detailed Setup

### Step 1: Install Laravel API Controller

```bash
cd /Users/davepeloso/Herd/notes

# Create directory if it doesn't exist
mkdir -p app/Http/Controllers/Api

# Copy the controller
cp ProjectSyncController.php app/Http/Controllers/Api/
```

**What it does:**
- Handles project sync from analyzer
- Creates/updates projects, notes, and tags
- Provides search and stats endpoints

---

### Step 2: Add API Routes

Open `routes/api.php` and add:

```php
use App\Http\Controllers\Api\ProjectSyncController;

Route::prefix('api')->group(function () {
    
    // Sync operations
    Route::post('/sync/projects', [ProjectSyncController::class, 'syncProjects']);
    Route::get('/sync/stats', [ProjectSyncController::class, 'stats']);
    
    // Project operations
    Route::get('/projects', [ProjectSyncController::class, 'index']);
    Route::get('/projects/search', [ProjectSyncController::class, 'search']);
    Route::get('/projects/{project}', [ProjectSyncController::class, 'show']);
});
```

**Verify routes:**
```bash
php artisan route:list --path=api
```

You should see:
```
POST   api/sync/projects
GET    api/sync/stats
GET    api/projects
GET    api/projects/search
GET    api/projects/{project}
```

---

### Step 3: Start Laravel Server

```bash
cd /Users/davepeloso/Herd/notes
php artisan serve

# Or if you use Valet:
# valet link
# API will be at: http://notes.test/apisync/stats
```

**Test the API:**
```bash
curl http://localhost:8000/api/sync/stats
# or
curl http://notes.test/api/sync/stats
```

You should see:
```json
{
  "success": true,
  "stats": {
    "total_projects": 0,
    "total_notes": 0,
    ...
  }
}
```

---

### Step 4: Install Enhanced Scripts

```bash
# Copy files to ~/.local/bin
cp project-organizer-ai.py ~/.local/bin/
cp sync-to-notes.py ~/.local/bin/

# Make executable
chmod +x ~/.local/bin/project-organizer-ai.py
chmod +x ~/.local/bin/sync-to-notes.py

# Create convenience symlinks
cd ~/.local/bin
ln -sf project-organizer-ai.py project-organizer-ai
ln -sf sync-to-notes.py sync-to-notes

# Verify installation
which project-organizer-ai
which sync-to-notes
```

---

### Step 5: Configure Environment

Add to your `~/.bashrc` or `~/.zshrc`:

```bash
# Gemini API (already set for project-organizer)
export GEMINI_API_KEY='your-key-here'

# Notes.test API URL
export NOTES_API_URL='http://notes.test/api'
# or if using php artisan serve:
# export NOTES_API_URL='http://localhost:8000/api'

# Optional: API authentication token (for future use)
# export NOTES_API_TOKEN='your-token-here'
```

Reload your shell:
```bash
source ~/.bashrc  # or ~/.zshrc
```

---

## 🎯 Usage Workflow

### Complete Monthly Analysis Flow

```bash
# 1. Scan your projects (unchanged)
project-organizer ~/Code

# 2. AI analysis (now generates sync data too!)
project-organizer-ai

# 3. Review what will be synced (dry-run)
sync-to-notes

# 4. Actually sync to Notes.test
sync-to-notes --execute

# 5. Open Notes.test to view
open http://notes.test/admin
```

---

## 📊 What Gets Created in Notes.test

### Projects
Each analyzed project becomes a **Project** in Notes.test:
- **Name**: `photography-gallery`
- **Description**: AI-generated summary
- **Color**: Based on primary tech stack

### Notes
Each project gets a detailed **Note**:
- **Title**: `photography-gallery [Analysis]`
- **Type**: Mixed (markdown + code)
- **Content**: Full markdown report
- **Code Content**: JSON metadata

### Tags
Tech stack and categories become **Tags**:
- `laravel` (red)
- `vue` (green)
- `client-facing` (purple)
- `web-apps` (blue)

### Flags
Status indicators become **Flags** (special tags):
- `revenue-generating` (gold)
- `needs-attention` (orange)
- `production` (green)
- `high-priority` (red)

---

## 🔍 Using Notes.test UI

### View All Projects
```
http://notes.test/admin/projects
```
See all synced projects with note counts

### View Project Notes
```
http://notes.test/admin/notes
```
Filter by:
- Project
- Tags (tech stack)
- Flags (status)

### Search Everything
Press `Cmd/Ctrl + K` for global search:
- Search project names
- Search documentation content
- Search tech stack
- Find specific features mentioned

### Filter by Tag
```
Click any tag badge → See all projects with that tag
```

Example filters:
- Show all Laravel projects
- Show all client-facing tools
- Show all projects needing attention

---

## 🔄 Re-sync Updates

Run the analyzer monthly to update project intelligence:

```bash
# Re-analyze everything
project-organizer ~/Code
project-organizer-ai
sync-to-notes --execute
```

**What happens:**
- Existing projects are **updated** (not duplicated)
- New projects are **added**
- Tags are **synced** (added/removed as needed)
- Documentation is **refreshed**

**Identification:**
Projects are matched by **name**, so renaming a project creates a new entry.

---

## 🛠️ Troubleshooting

### "Cannot connect to Notes.test API"

**Check Laravel is running:**
```bash
cd /Users/davepeloso/Herd/notes
php artisan serve
```

**Check routes are registered:**
```bash
php artisan route:list --path=api
```

**Test API directly:**
```bash
curl http://localhost:8000/api/sync/stats
```

---

### "sync-to-notes: command not found"

**Check PATH:**
```bash
echo $PATH | grep ".local/bin"
```

**If not in PATH, add to shell config:**
```bash
echo 'export PATH="$HOME/.local/bin:$PATH"' >> ~/.bashrc
source ~/.bashrc
```

---

### "Sync data file not found"

You need to run the analyzer first:
```bash
project-organizer ~/Code
project-organizer-ai
```

---

### "API returns 500 error"

**Check Laravel logs:**
```bash
tail -f /Users/davepeloso/Herd/notes/storage/logs/laravel.log
```

Common issues:
- Database not migrated: `php artisan migrate`
- Missing controller: Check file location
- Routes not loaded: Clear cache with `php artisan route:clear`

---

## 🎨 Customization

### Change Tech Stack Colors

Edit `project-organizer-ai.py`:

```python
TECH_COLORS = {
    "laravel": "#f05340",
    "vue": "#42b883",
    "your-framework": "#yourcolor",
}
```

### Change Flag Colors

```python
FLAG_COLORS = {
    "revenue-generating": "#fbbf24",
    "your-flag": "#yourcolor",
}
```

### Add Custom Status Flags

In the AI prompt (within `project-organizer-ai.py`), add your flag to the list:

```python
status_flags: Array of status indicators:
  - revenue-generating
  - your-custom-flag
  - another-flag
```

---

## 📚 API Reference

### POST /api/sync/projects

Sync multiple projects at once.

**Request:**
```json
{
  "projects": [
    {
      "project_data": {
        "name": "my-project",
        "description": "...",
        "color": "#3b82f6"
      },
      "note_data": {
        "title": "my-project [Analysis]",
        "type": "mixed",
        "content": "# Markdown content",
        "code_content": "{...json...}"
      },
      "tags": [
        {"name": "laravel", "color": "#f05340", "is_flag": false}
      ],
      "flags": [
        {"name": "production", "color": "#10b981", "is_flag": true}
      ]
    }
  ]
}
```

**Response:**
```json
{
  "success": true,
  "synced": 1,
  "results": [
    {
      "project_id": 1,
      "project_name": "my-project",
      "note_id": 1,
      "note_title": "my-project [Analysis]",
      "tags_attached": 2
    }
  ]
}
```

---

### GET /api/sync/stats

Get sync statistics.

**Response:**
```json
{
  "success": true,
  "stats": {
    "total_projects": 12,
    "total_notes": 15,
    "total_tags": 8,
    "total_flags": 4,
    "projects_with_notes": 12,
    "recent_syncs": [...]
  }
}
```

---

### GET /api/projects/search

Search projects by tag or flag.

**Query params:**
- `tag`: Filter by tag name
- `flag`: Filter by flag name
- `q`: Text search in name/description

**Example:**
```bash
curl "http://notes.test/api/projects/search?tag=laravel&flag=production"
```

---

## 🎉 Success!

You now have a complete Project Intelligence System!

### What you can do:
- ✅ Scan all your code projects
- ✅ AI-powered analysis and categorization
- ✅ Rich metadata in searchable database
- ✅ Multi-dimensional tagging (tech + business + status)
- ✅ Beautiful UI for exploring your portfolio
- ✅ Full-text search across all documentation
- ✅ Monthly re-sync to keep intelligence fresh

### Next steps:
1. Run your first analysis
2. Explore projects in Notes.test
3. Use tags/flags to organize your workflow
4. Schedule monthly re-analysis

Enjoy your organized development portfolio! 🚀
files ./install.sh

[INFO] Project Analyzer → Notes.test Integration
[INFO] ==========================================

[SUCCESS] Found Notes.test at: /Users/davepeloso/Herd/notes

▸ Installing API Controller...
[SUCCESS] API Controller installed

▸ Checking API routes...
[WARNING] API routes not found
[INFO] Please manually add routes to: /Users/davepeloso/Herd/notes/routes/api.php
[INFO] See: /Users/Shared/Projects/files/api-routes.php

▸ Installing Python scripts...
[SUCCESS] Enhanced analyzer installed
[SUCCESS] Sync script installed

▸ Checking environment...
[SUCCESS] GEMINI_API_KEY is set
[INFO] NOTES_API_URL not set (using default: http://notes.test/api)
[INFO] To change: export NOTES_API_URL='http://localhost:8000/api'

▸ Testing Laravel API...
[WARNING] Laravel API not responding
[INFO] Start Laravel with:
 cd /Users/davepeloso/Herd/notes
 php artisan serve

[SUCCESS] Installation complete!

[INFO] Files installed:
▸ API Controller: /Users/davepeloso/Herd/notes/app/Http/Controllers/Api/ProjectSyncController.php
▸ Enhanced Analyzer: /Users/davepeloso/.local/bin/project-organizer-ai
▸ Sync Script: /Users/davepeloso/.local/bin/sync-to-notes

[INFO] Quick Start:

 1. Add API routes (see /Users/Shared/Projects/files/api-routes.php)

 2. Start Laravel:
    cd /Users/davepeloso/Herd/notes
    php artisan serve

 3. Verify API:
    curl http://localhost:8000/api/sync/stats

 4. Run analysis:
    project-organizer ~/Code
    project-organizer-ai

 5. Sync to Notes.test:
    sync-to-notes              # Dry-run preview
    sync-to-notes --execute    # Actually sync

 6. View in Notes.test:
    open http://notes.test/admin/projects

[INFO] Full documentation: /Users/Shared/Projects/files/INSTALLATION.md

➜  files
