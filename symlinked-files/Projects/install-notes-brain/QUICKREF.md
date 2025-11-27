# Quick Reference - Project Analyzer → Notes.test

## 🚀 Daily Commands

```bash
# Monthly analysis workflow
project-organizer ~/Code           # Scan projects
project-organizer-ai               # AI analysis
sync-to-notes                      # Preview sync (dry-run)
sync-to-notes --execute            # Actually sync

# View results
open http://notes.test/admin/projects
```

## 📊 API Endpoints

```bash
# Check stats
curl http://notes.test/api/sync/stats

# Sync projects
curl -X POST http://notes.test/api/sync/projects \
  -H "Content-Type: application/json" \
  -d @~/.project_data/notes_sync_data.json

# Search by tag
curl "http://notes.test/api/projects/search?tag=laravel"

# Search by flag
curl "http://notes.test/api/projects/search?flag=production"

# Text search
curl "http://notes.test/api/projects/search?q=gallery"

# Get all projects
curl http://notes.test/api/projects

# Get specific project
curl http://notes.test/api/projects/1
```

## 🏷️ Common Tags

**Tech Stack:**
- `laravel`, `php`, `vue`, `react`, `nextjs`, `angular`
- `python`, `node`, `bash`
- `docker`, `go`, `rust`, `ruby`

**Business Context:**
- `client-facing` - Customer-facing tools
- `internal-tool` - Internal business tools
- `automation` - Workflow automation
- `research` - Experimental/learning

**Categories:**
- `web-apps`, `scripts`, `infrastructure`
- `libraries`, `documentation`, `research`

## 🚩 Common Flags

- `revenue-generating` - Directly makes money
- `client-facing` - Customers interact with it
- `needs-attention` - Has tech debt/issues
- `high-priority` - Critical to business
- `production` - In active use
- `maintenance` - Stable, occasional updates
- `experimental` - Prototype/learning
- `deprecated` - No longer used

## 📁 File Locations

```
~/.local/bin/
├── project-organizer.sh          # Scanner
├── project-organizer-ai          # AI analyzer (enhanced)
└── sync-to-notes                 # Sync to Notes.test

~/.project_data/
├── projects.json                 # Raw scan data
├── organization_proposal.md      # Human report
└── notes_sync_data.json         # API-ready data

/Users/Shared/Projects/notes/
├── app/Http/Controllers/Api/
│   └── ProjectSyncController.php # API controller
└── routes/
    └── api.php                   # API routes
```

## 🔍 Notes.test UI

**Global Search:** `Cmd/Ctrl + K`

**Projects List:**
`http://notes.test/admin/projects`

**Notes List:**
`http://notes.test/admin/notes`
- Filter by project
- Filter by tags
- Filter by type

**Tags Management:**
`http://notes.test/admin/tags`
- View all tags
- See tag usage
- Distinguish flags (special tags)

## ⚙️ Environment Variables

```bash
# Required
export GEMINI_API_KEY='your-key-here'

# Optional (defaults shown)
export NOTES_API_URL='http://notes.test/api'
export NOTES_API_TOKEN='your-token'  # For future auth
export MAX_DEPTH=4                    # Scan depth
```

## 🔧 Troubleshooting

### "Cannot connect to API"
```bash
cd /Users/Shared/Projects/notes
php artisan serve
curl http://localhost:8000/api/sync/stats
```

### "Command not found"
```bash
export PATH="$HOME/.local/bin:$PATH"
source ~/.bashrc
```

### "No sync data found"
```bash
project-organizer ~/Code
project-organizer-ai
```

### "API returns 500"
```bash
cd /Users/Shared/Projects/notes
tail -f storage/logs/laravel.log
php artisan route:clear
```

## 🎯 Common Workflows

### Initial Setup
```bash
# 1. Install integration
cd /path/to/integration
./install.sh

# 2. Add API routes
# Edit: /Users/Shared/Projects/notes/routes/api.php
# Copy from: api-routes.php

# 3. Start Laravel
cd /Users/Shared/Projects/notes
php artisan serve

# 4. First sync
project-organizer ~/Code
project-organizer-ai
sync-to-notes --execute
```

### Monthly Update
```bash
# Re-analyze and sync
project-organizer ~/Code
project-organizer-ai
sync-to-notes --execute

# View updated data
open http://notes.test/admin
```

### Find Projects Needing Attention
```bash
# Via API
curl "http://notes.test/api/projects/search?flag=needs-attention"

# Via UI
# 1. Go to: http://notes.test/admin/notes
# 2. Filter by flag: "needs-attention"
```

### Find All Laravel Projects
```bash
# Via API
curl "http://notes.test/api/projects/search?tag=laravel"

# Via UI
# 1. Go to: http://notes.test/admin/notes
# 2. Filter by tag: "laravel"
```

### Search Documentation
```bash
# Via UI
# 1. Press Cmd/Ctrl + K
# 2. Type search term (e.g., "stripe", "gallery", "payment")
# 3. Results show in all project documentation
```

## 📈 Data Flow

```
Code Projects
    ↓
project-organizer.sh (scan)
    ↓
projects.json
    ↓
project-organizer-ai (analyze)
    ↓
notes_sync_data.json
    ↓
sync-to-notes (push)
    ↓
Notes.test Database
    ↓
Filament UI (view/search)
```

## 🎨 Customization

### Change Colors
Edit `project-organizer-ai.py`:
```python
TECH_COLORS = {
    "your-tech": "#yourcolor"
}

FLAG_COLORS = {
    "your-flag": "#yourcolor"
}
```

### Change Categories
Edit AI prompt in `project-organizer-ai.py`:
```python
prompt = f"""
...
category: your-category-1, your-category-2, ...
...
"""
```

### Change Batch Size
Edit `project-organizer-ai.py`:
```python
BATCH_SIZE = 10  # Default is 5
```

## 💡 Tips

1. **Run monthly** - Keep intelligence fresh
2. **Use flags** - Quickly find projects needing attention
3. **Tag everything** - Makes searching powerful
4. **Review proposal first** - Always check before syncing
5. **Dry-run first** - Preview with `sync-to-notes` (no --execute)
6. **Search globally** - Cmd/Ctrl + K is your friend
7. **Filter creatively** - Combine tags + flags for powerful queries

## 📚 Documentation

- **INSTALLATION.md** - Full setup guide
- **api-routes.php** - Route definitions
- **ProjectSyncController.php** - API logic

---

**Need help?** Check the full documentation in `INSTALLATION.md`
