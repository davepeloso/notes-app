# Project Analyzer → Notes.test Integration

Complete integration package to sync your analyzed code projects into your Notes.test Laravel application for powerful organization, tagging, and search.

## 🎯 What This Does

Transforms your Project Organizer from a file-moving tool into an **intelligent project portfolio system**:

- **Non-destructive**: Projects stay where they are
- **Rich metadata**: Deep documentation analysis stored in database
- **Multi-dimensional**: Tag by tech stack, business value, status
- **Searchable**: Full-text search across all project documentation
- **Visual**: Beautiful Filament UI for exploring your portfolio
- **Living documentation**: Re-sync monthly to keep fresh

## 📦 Package Contents

```
project-analyzer-integration/
├── ProjectSyncController.php    # Laravel API controller
├── api-routes.php               # API route definitions
├── project-organizer-ai.py      # Enhanced analyzer with Notes output
├── sync-to-notes.py            # Sync script
├── install.sh                  # Automated installation
├── INSTALLATION.md             # Complete setup guide
├── QUICKREF.md                 # Quick reference cheat sheet
├── example_sync_data.json      # Example data format
└── README.md                   # This file
```

## ⚡ Quick Start

```bash
# 1. Run the installer
cd project-analyzer-integration
./install.sh

# 2. Add API routes to Notes.test
# See INSTALLATION.md for details

# 3. Start Laravel
cd /Users/Shared/Projects/notes
php artisan serve

# 4. Analyze and sync
project-organizer ~/Code
project-organizer-ai
sync-to-notes --execute

# 5. View in Notes.test
open http://notes.test/admin/projects
```

## 🏗️ Architecture

```
Your Code Projects
       ↓
project-organizer.sh ────→ projects.json (raw scan data)
       ↓
project-organizer-ai ────→ notes_sync_data.json (API-ready)
       ↓
sync-to-notes ───────────→ Notes.test API
       ↓
MySQL Database
       ↓
Filament UI (search, filter, explore)
```

## 📊 Data Model

### Project
- Name: `photography-gallery`
- Description: AI-generated summary
- Color: Based on tech stack
- Has many: Notes

### Note (per project)
- Title: `photography-gallery [Analysis]`
- Type: Mixed (markdown + JSON)
- Content: Full documentation report
- Code Content: Structured metadata
- Belongs to: Project
- Has many: Tags (including Flags)

### Tag
- Name: `laravel`, `vue`, `client-facing`, etc.
- Color: Hex color code
- Is Flag: Boolean (false for regular tags)

### Flag (special Tag)
- Name: `revenue-generating`, `needs-attention`, etc.
- Color: Status-based colors
- Is Flag: Boolean (true)

## 🎨 Features

### For Notes.test
✅ RESTful API endpoints  
✅ Batch project sync  
✅ Tag and flag management  
✅ Search and filtering  
✅ Statistics dashboard  

### For Project Analyzer
✅ Enhanced AI analysis  
✅ Business context detection  
✅ Status flag generation  
✅ Structured JSON output  
✅ Color-coded tech stack  

### For You
✅ Beautiful UI in Filament  
✅ Global search (Cmd/Ctrl + K)  
✅ Multi-tag filtering  
✅ Non-destructive organization  
✅ Monthly re-sync updates  

## 📖 Documentation

### Full Guides
- **[INSTALLATION.md](INSTALLATION.md)** - Complete setup instructions
- **[QUICKREF.md](QUICKREF.md)** - Quick reference cheat sheet

### Code Documentation
- **[ProjectSyncController.php](ProjectSyncController.php)** - API endpoint logic
- **[project-organizer-ai.py](project-organizer-ai.py)** - Enhanced analyzer
- **[sync-to-notes.py](sync-to-notes.py)** - Sync script

### Examples
- **[example_sync_data.json](example_sync_data.json)** - Sample data format

## 🔧 Requirements

### System
- macOS or Linux
- Bash shell
- Python 3.8+
- PHP 8.2+
- Composer

### Applications
- Project Organizer (installed)
- Notes.test Laravel app
- Google Gemini API key

### PHP Packages (already in Notes.test)
- Laravel 11.x
- Filament 3.x

### Python Packages
- requests

## 🚀 Installation

### Option 1: Automated (Recommended)

```bash
cd project-analyzer-integration
./install.sh
```

Follow the prompts and instructions.

### Option 2: Manual

See [INSTALLATION.md](INSTALLATION.md) for detailed step-by-step instructions.

## 💡 Use Cases

### Photography Studio Owner (You!)
- Track all your development projects
- Flag revenue-generating tools
- Find client-facing applications
- Identify automation opportunities
- Manage tech debt

### Freelance Developer
- Portfolio management
- Client project tracking
- Technology inventory
- Project health monitoring

### Small Dev Team
- Shared project knowledge base
- Onboarding documentation
- Technical debt tracking
- Resource planning

## 🎯 Example Workflows

### Monthly Portfolio Review
```bash
# 1. Re-analyze everything
project-organizer ~/Code
project-organizer-ai
sync-to-notes --execute

# 2. Review in Notes.test
open http://notes.test/admin

# 3. Check flagged projects
# Filter by: needs-attention flag

# 4. Plan your month
# Prioritize high-value, needs-attention projects
```

### Find Revenue-Generating Laravel Projects
```bash
# Via API
curl "http://notes.test/api/projects/search?tag=laravel&flag=revenue-generating"

# Via UI
# 1. Go to Notes
# 2. Filter by tag: laravel
# 3. Filter by flag: revenue-generating
```

### Research Documentation
```bash
# Press Cmd/Ctrl + K in Notes.test
# Search: "stripe integration"
# See all projects mentioning Stripe
```

## 🔐 Security Note

**Current version has NO authentication** on API endpoints. This is fine for:
- Local development
- Internal networks
- Single-user systems

For production/multi-user, add Laravel Sanctum authentication:

```php
Route::middleware(['auth:sanctum'])->group(function () {
    // All API routes here
});
```

See Laravel Sanctum docs: https://laravel.com/docs/sanctum

## 🐛 Troubleshooting

Common issues and solutions:

### "Cannot connect to Notes.test API"
```bash
cd /Users/Shared/Projects/notes
php artisan serve
```

### "Command not found"
```bash
echo 'export PATH="$HOME/.local/bin:$PATH"' >> ~/.bashrc
source ~/.bashrc
```

### "No sync data found"
```bash
project-organizer ~/Code
project-organizer-ai
```

### "API returns 500 error"
```bash
tail -f /Users/Shared/Projects/notes/storage/logs/laravel.log
php artisan route:clear
php artisan config:clear
```

More troubleshooting in [INSTALLATION.md](INSTALLATION.md#troubleshooting)

## 🎨 Customization

### Tech Stack Colors
Edit `project-organizer-ai.py`:
```python
TECH_COLORS = {
    "laravel": "#f05340",
    "your-tech": "#yourcolor",
}
```

### Flag Colors
```python
FLAG_COLORS = {
    "revenue-generating": "#fbbf24",
    "your-flag": "#yourcolor",
}
```

### Categories
Modify AI prompt in `project-organizer-ai.py`

### Batch Size
```python
BATCH_SIZE = 10  # More projects per API call
```

## 🗺️ Roadmap

Potential future enhancements:

- [ ] Authentication with Laravel Sanctum
- [ ] Web UI for sync (instead of CLI)
- [ ] Scheduled auto-sync (cron/systemd)
- [ ] GitHub integration (stars, issues, PRs)
- [ ] Project health scoring
- [ ] Dependency analysis
- [ ] Screenshot capture
- [ ] Architecture diagram detection
- [ ] Custom AI prompts per project type
- [ ] Export to various formats

## 🤝 Contributing

This integration was built for a specific use case (photography studio owner who codes), but contributions are welcome!

Areas for improvement:
- Additional project types
- Custom categorization rules
- Better documentation parsing
- Photography-specific features
- Team collaboration features

## 📝 License

MIT License - feel free to use, modify, and distribute.

## 🙏 Credits

Built on top of:
- **Project Organizer** - Original file scanning system
- **Notes.test** - Laravel note-taking app with Filament
- **Google Gemini** - AI analysis
- **Filament** - Beautiful Laravel admin UI

Created for photographers and developers who need intelligent project organization without disrupting their existing workflows.

## 📞 Support

Questions or issues?

1. Check [INSTALLATION.md](INSTALLATION.md)
2. Check [QUICKREF.md](QUICKREF.md)
3. Review Laravel logs: `tail -f storage/logs/laravel.log`
4. Test API directly: `curl http://notes.test/api/sync/stats`

## 🎉 That's It!

You now have everything you need to transform your scattered projects into an organized, searchable, intelligent portfolio.

**Next steps:**
1. Run `./install.sh`
2. Follow the setup instructions
3. Analyze your first project
4. Explore in Notes.test

Happy organizing! 🚀
