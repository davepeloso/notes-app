# 🚀 Project Organizer + Notes.test - Quick Reference

## The Complete System in 30 Seconds

```
YOUR PROJECTS → SCANNER → JSON → NOTES.TEST → ORGANIZED!
  (scattered)    (AI)    (data)   (dashboard)  (searchable)
```

## One-Time Setup (5 minutes)

```bash
# 1. Install Scanner
cd ~/project-organizer && ./setup.sh

# 2. Get API Key  
# Visit: https://makersuite.google.com/app/apikey
export GEMINI_API_KEY='your-key'

# 3. Setup Notes.test
cd ~/Herd/Notes.test
composer install && php artisan migrate
```

## Daily Workflow

```bash
# STEP 1: Scan (finds all your projects)
project-organizer ~/Herd

# STEP 2: Analyze (AI categorizes them)
project-organizer-ai

# STEP 3: View in Dashboard
# Open: https://notes.test
# Import: ~/.project_data/projects.json
```

## Common Commands

```bash
# Scan everything
project-organizer ~/Herd          # All projects in Herd
project-organizer ~/Code          # All projects in Code
project-organizer .               # Current directory

# Scan single project
project-organizer ~/Herd/agro     # Just the agro project

# Health check first (recommended)
project-prescan ~/Herd            # Check for issues

# View results
cat ~/.project_data/projects.json | jq '.'
cat ~/.project_data/organization_proposal.md
```

## Scanner Output → Dashboard Fields

| Scanner Extracts        | Notes.test Shows         |
|------------------------|--------------------------|
| name                   | Project Name             |
| original_path          | Location                 |
| types (laravel,php)    | Technology Tags          |
| description (AI)       | Description              |
| git.remote             | Repository Link          |
| file_count             | Project Size             |
| last_modified          | Last Activity            |
| documentation          | README Preview           |

## Notes.test Dashboard Features

**Search & Filter:**
- 🔍 Search: "laravel" → Shows all Laravel projects
- 🏷️ Filter by tag: "photography", "revenue-generating"
- 📊 Filter by status: "active", "needs-attention"

**Organize:**
- Add custom tags
- Mark revenue-generating projects
- Flag projects needing attention
- Archive old experiments
- Link related projects

**Find Quickly:**
- Search across all metadata
- Filter by technology
- Sort by last modified
- View git status

## Photography Studio Workflow

```bash
# Morning: Check what needs attention
# Dashboard → Filter: "needs-attention"

# Client calls about gallery app
# Dashboard → Search: "gallery"
# See: location, git remote, last modified

# New project started
project-organizer ~/Herd/new-project
# Dashboard → Refresh → Tag: "client-work"

# End of week: Review
# Dashboard → View all revenue-generating projects
```

## Integration Options

### Option 1: Manual Import
```bash
project-organizer ~/Herd
project-organizer-ai
# In Notes.test: Import → Choose projects.json
```

### Option 2: API Sync (Automated)
```bash
project-organizer ~/Herd
project-organizer-ai
curl -X POST https://notes.test/api/projects/sync \
  -d @~/.project_data/projects.json
```

### Option 3: Scheduled Sync
```bash
# Add to crontab
0 2 * * 0 project-organizer ~/Herd && \
          project-organizer-ai && \
          curl -X POST https://notes.test/api/projects/sync \
          -d @~/.project_data/projects.json
# Runs weekly on Sunday 2 AM
```

## File Locations

```
~/.project_data/
├── projects.json              # Raw scan data
├── organization_proposal.md   # AI suggestions
└── reorganize.sh             # Optional move script (unused)

~/Herd/Notes.test/            # Laravel dashboard
├── app/Models/Project.php    # Project model
├── app/Filament/             # Dashboard UI
└── database/                 # SQLite database
```

## Troubleshooting Checklist

**Scanner not working?**
```bash
□ API key set? echo $GEMINI_API_KEY
□ Dependencies installed? which tree jq git python3
□ PATH correct? echo $PATH | grep .local/bin
□ JSON clean? cat ~/.project_data/projects.json | jq '.'
```

**Notes.test not showing projects?**
```bash
□ Database migrated? php artisan migrate:status
□ Data imported? Check import logs
□ Cache cleared? php artisan cache:clear
□ Search index built? Check Filament settings
```

## Key Benefits

| Feature | Scanner | Notes.test |
|---------|---------|------------|
| **Find Projects** | ✅ Discovers | ✅ Searches |
| **Categorize** | ✅ AI-powered | ✅ Custom tags |
| **Track Changes** | ✅ Git status | ✅ Activity log |
| **Organization** | ✅ Metadata | ✅ No moving |
| **Team Access** | ❌ Local only | ✅ Web dashboard |
| **Search** | ❌ JSON only | ✅ Full-text |

## Best Practices

### For Photographers 📸
1. Tag projects: "client-work", "personal", "experiments"
2. Mark revenue-generating projects
3. Search when clients call
4. Archive completed shoots
5. Rescan monthly

### For Developers 💻
1. Use tech tags automatically
2. Track git status
3. Link dependencies
4. Find stale projects
5. Monitor file counts

## Quick Wins

**Today:**
- ✅ Scan all projects once
- ✅ Import to Notes.test
- ✅ Tag 5 most important projects

**This Week:**
- ✅ Add all custom tags
- ✅ Mark revenue projects
- ✅ Archive old work
- ✅ Test search features

**Ongoing:**
- ✅ Rescan monthly
- ✅ Update tags as needed
- ✅ Track project health
- ✅ Use search daily

## Resources

- **Full Docs:** README.md
- **Quick Start:** QUICKSTART.md  
- **Commands:** USAGE.md
- **Debugging:** DEBUGGING_GUIDE.md
- **API Key:** https://makersuite.google.com/app/apikey

## Get Help

**Common Issues:**
- JSON corrupted? → Remove and rescan
- Can't find projects? → Increase MAX_DEPTH
- Scanner fails? → Run project-prescan first
- Dashboard empty? → Check import/sync

**Support:**
- Read docs in ~/project-organizer/
- Check troubleshooting sections
- Review example output

---

**Remember:** Projects stay where they are. You're just organizing metadata! 🎯
