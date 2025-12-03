# Project Organizer + Notes.test Integration

A comprehensive system for organizing scattered code projects using AI-powered scanning and a centralized Laravel dashboard. Perfect for photographers running studios with multiple code projects, or any developer drowning in disorganized repositories.

## 🎯 The Complete System

```
┌─────────────────────────────────────────────────────────────────┐
│                    YOUR SCATTERED PROJECTS                      │
│  ~/Herd/agro  ~/Code/gallery  ~/Downloads/old-stuff  etc.       │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│              PROJECT ORGANIZER SCANNER (Bash/Python)            │
│  • Recursively scans directories                                │
│  • Detects project types (Laravel, Next.js, Python, etc.)      │
│  • Extracts metadata (git info, docs, file counts)             │
│  • AI categorization via Google Gemini API                      │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    PROJECTS JSON DATABASE                       │
│              ~/.project_data/projects.json                      │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│              NOTES.TEST LARAVEL APPLICATION                     │
│  • Filament 4.2 admin dashboard                                 │
│  • Multi-dimensional tagging system                             │
│  • Project status tracking (revenue-generating, needs-attention) │
│  • Search and filter projects                                   │
│  • Markdown editing with live preview                           │
│  • PHP Monaco editor for code                                   │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│              ORGANIZED PROJECT MANAGEMENT                       │
│  • Find projects by tags, types, status                         │
│  • Track revenue-generating work                                │
│  • Identify projects needing attention                          │
│  • Search across all project metadata                           │
│  • NO FILE MOVING - Metadata-based organization                │
└─────────────────────────────────────────────────────────────────┘
```

## ✨ Key Features

### Project Organizer Scanner
- **🔍 Intelligent Scanning**: Recursively finds projects by detecting markers (`.git`, `package.json`, etc.)
- **🤖 AI Analysis**: Uses Google Gemini to suggest meaningful names and categories
- **📊 Rich Metadata**: Extracts git info, documentation, tree structure, file counts
- **🎯 Single or Multi-Project**: Scan one project or entire directories
- **🗂️ Smart Categorization**: Automatically organizes into web-apps, scripts, infrastructure, etc.
- **🛡️ Safety First**: Dry-run by default, automatic backups, git awareness
- **⚡ Pre-Scan Tool**: Detects problematic directories before running full scan

### Notes.test Laravel Dashboard
- **📱 Filament 4.2 UI**: Modern, responsive admin interface
- **🏷️ Multi-Dimensional Tags**: Categorize projects with custom tags
- **🎨 Status Tracking**: Flag projects as revenue-generating, needs-attention, archived, etc.
- **🔍 Global Search**: Find projects across all metadata fields
- **📝 Markdown Editor**: Live preview for documentation
- **💻 Code Editor**: PHP Monaco editor with syntax highlighting
- **🔄 Sync Integration**: Import scanned projects with one command
- **📍 Location Preservation**: Projects stay where they are - no file moving

## 🚀 Quick Start

### Prerequisites

- macOS or Linux
- PHP 8.2+ with Laravel 11
- Node.js 18+
- Python 3.9+
- Git, jq, tree
- Google Gemini API key (free)

### Part 1: Install Project Organizer Scanner

```bash
# 1. Clone or download the scripts
cd ~/project-organizer
./setup.sh

# 2. Get your Gemini API key
# Visit: https://makersuite.google.com/app/apikey

# 3. Export the API key
export GEMINI_API_KEY='your-key-here'
echo 'export GEMINI_API_KEY="your-key-here"' >> ~/.bashrc

# 4. Verify installation
which project-organizer
# Should show: /Users/you/.local/bin/project-organizer
```

### Part 2: Set Up Notes.test Laravel App

```bash
# 1. Set up your Laravel application
cd ~/Herd/Notes.test
composer install
npm install && npm run build

# 2. Configure environment
cp .env.example .env
php artisan key:generate

# 3. Set up database
php artisan migrate

# 4. Start the app
# If using Laravel Herd: https://notes.test
# Or: php artisan serve
```

### Part 3: Scan and Sync Your Projects

```bash
# 1. Run a health check (optional but recommended)
project-prescan ~/Herd

# 2. Scan your projects
cd ~/Herd
project-organizer .

# 3. Run AI analysis
project-organizer-ai

# 4. Review the proposal
cat ~/.project_data/organization_proposal.md

# 5. Sync to Notes.test dashboard
# Via Laravel app API endpoint or import feature
# (Implementation depends on your Notes.test setup)
```

## 📖 Complete Workflow

### 1. Discovery Phase

**Scan your scattered projects:**
```bash
# Scan work projects
project-organizer ~/Herd

# Scan personal projects  
project-organizer ~/Code

# Scan experiments
project-organizer ~/Downloads/test-projects

# Scan a single project
project-organizer ~/Herd/gallery-app
```

**Output:** `~/.project_data/projects.json` with complete metadata

### 2. AI Analysis Phase

**Categorize and organize:**
```bash
# Run AI analysis
project-organizer-ai

# Output:
# - ~/.project_data/organization_proposal.md (human-readable)
# - ~/.project_data/reorganize.sh (optional move script)
```

**AI determines:**
- Meaningful project names
- Appropriate categories (web-apps, scripts, infrastructure, etc.)
- Brief descriptions
- Confidence levels

### 3. Dashboard Management Phase

**Import to Notes.test:**

The Notes.test Laravel application provides a centralized dashboard where you can:

- **View all projects** in a filterable table
- **Tag projects** with custom categories
- **Track status** (active, archived, revenue-generating, needs-attention)
- **Search everything** - names, descriptions, paths, types
- **Edit metadata** - Update descriptions, add notes
- **Link related projects** - Connect dependencies
- **Monitor activity** - Last modified, file counts, sizes

**Projects stay in their original locations** - Notes.test just manages the metadata.

### 4. Ongoing Management

**Keep your database updated:**
```bash
# Re-scan when you add new projects
project-organizer ~/NewProjectsFolder

# Re-analyze with AI
project-organizer-ai

# Sync to dashboard
# (Re-import or use API sync feature)
```

**In Notes.test Dashboard:**
- Add tags as projects evolve
- Update status flags
- Track which projects generate revenue
- Identify projects needing attention
- Archive old experiments

## 🎯 Use Cases

### For Photography Studio Owners

**Scenario:** You're a photographer with multiple Laravel apps, image processing scripts, booking systems, and client management tools scattered across your system.

**Before Project Organizer + Notes.test:**
```
📁 Random locations
  - ~/Herd/gallery2023
  - ~/Code/img_processor
  - ~/Downloads/booking_app
  - ~/Desktop/old-portfolio
  - Can't remember what's what
  - Don't know which projects are revenue-generating
  - Duplicates and abandoned experiments everywhere
```

**After Project Organizer + Notes.test:**
```
✅ All projects cataloged in Notes.test dashboard
✅ Tagged: photography, client-work, internal-tools, experiments
✅ Status: revenue-generating projects marked
✅ Search: "booking" finds all booking-related projects
✅ Organized: Clear categorization without moving files
✅ Tracked: Know what needs maintenance, what's archived
```

**Workflow:**
1. **Scan once:** `project-organizer ~/Herd`
2. **Import to Notes.test:** View in dashboard
3. **Tag projects:** "revenue-generating", "client-facing", "internal"
4. **Track status:** Mark critical projects
5. **Find quickly:** Search when clients call
6. **Rescan periodically:** Keep metadata fresh

### For Developers with Messy Code Directories

**Organize scattered repositories:**
- Scan multiple directories
- AI categorizes by technology (Laravel, Next.js, Python)
- Import to Notes.test for easy searching
- Tag by client, project type, status
- Find projects instantly without remembering paths

### For Teams

**Shared project knowledge:**
- Scan team codebases
- Centralized dashboard in Notes.test
- Everyone sees project metadata
- Tag by team ownership
- Track project status collaboratively

## 🏗️ Architecture

### Scanner Component (Bash/Python)

**Files:**
- `project-organizer.sh` - Main scanner (bash)
- `project-organizer-ai.py` - AI analysis (Python + Gemini API)
- `project-prescan.sh` - Health check tool
- `setup.sh` - Installation script

**Data Flow:**
1. Bash script recursively scans directories
2. Detects project markers (.git, package.json, etc.)
3. Extracts metadata (git info, docs, tree structure)
4. Saves to JSON: `~/.project_data/projects.json`
5. Python script sends to Gemini API
6. AI returns categorization and descriptions
7. Generates proposal and reorganization script

### Notes.test Laravel Component

**Technology Stack:**
- Laravel 11
- Filament 4.2 (admin panel)
- PHP Monaco (code editor)
- Markdown with live preview
- Multi-dimensional tagging system

**Features:**
- Project model with metadata fields
- Tag relationships (many-to-many)
- Status flags (revenue-generating, needs-attention)
- Global search across all fields
- Filament resources for CRUD operations
- JSON import capability
- API endpoints for scanner integration

**Database Schema:**
```sql
projects
  - id
  - name
  - original_path
  - types (json)
  - description
  - file_count
  - size
  - last_modified
  - git_remote
  - git_branch
  - has_uncommitted_changes
  - tree_structure (text)
  - documentation (text)
  - analyzed_at
  - created_at
  - updated_at

project_tag
  - project_id
  - tag_id

tags
  - id
  - name
  - slug
  - color
```

## 🔗 Integration Points

### 1. JSON Import

**Manual Import:**
```bash
# After scanning
cp ~/.project_data/projects.json /path/to/Notes.test/storage/app/imports/

# In Notes.test
php artisan projects:import storage/app/imports/projects.json
```

### 2. API Sync (Recommended)

**Create API endpoint in Notes.test:**
```php
// routes/api.php
Route::post('/projects/sync', [ProjectController::class, 'sync']);
```

**Scanner can POST directly:**
```python
# In project-organizer-ai.py
import requests

def sync_to_notes_app(projects):
    response = requests.post(
        'https://notes.test/api/projects/sync',
        json={'projects': projects}
    )
    return response.json()
```

### 3. Automated Sync Script

```bash
#!/bin/bash
# sync-to-notes.sh

# Scan projects
project-organizer ~/Herd

# AI analysis
project-organizer-ai

# Sync to Notes.test
curl -X POST https://notes.test/api/projects/sync \
  -H "Content-Type: application/json" \
  -d @~/.project_data/projects.json
```

## 📊 Example Output

### Scanner JSON Output
```json
[
  {
    "name": "agro",
    "original_path": "/Users/dave/Herd/agro",
    "types": "laravel,php,docker",
    "file_count": 847,
    "size": "45M",
    "last_modified": "2025-12-02",
    "tree_structure": "agro/\n├── app/\n├── config/\n...",
    "documentation": "# Agro\n\nA Laravel application for...",
    "git": {
      "branch": "main",
      "remote": "git@github.com:user/agro.git",
      "has_uncommitted_changes": false
    },
    "analyzed_at": "2025-12-03T03:30:00Z",
    "ai_analysis": {
      "suggested_name": "agro-management-system",
      "category": "web-apps",
      "description": "Laravel-based agricultural management system",
      "confidence": "high"
    }
  }
]
```

### Notes.test Dashboard View

**Projects Table:**
```
Name                    Tags                        Status              Path
agro                    laravel, work, active       revenue-generating  ~/Herd/agro
gallery-app             photography, laravel        active              ~/Herd/gallery
image-processor         python, automation          needs-attention     ~/Code/processor
booking-system          nextjs, client-facing       revenue-generating  ~/Herd/booking
old-experiment          archived                    archived            ~/Downloads/test
```

**Search:** Type "laravel" → Shows agro, gallery-app  
**Filter:** Status = "revenue-generating" → Shows agro, booking-system  
**Tags:** Click "photography" → Shows all photo-related projects

## 🛠️ Configuration

### Scanner Configuration

**Environment variables:**
```bash
# Scan depth (default: 4)
export MAX_DEPTH=6

# Gemini API key (required for AI analysis)
export GEMINI_API_KEY='your-key-here'
```

**Customize categories** in `project-organizer-ai.py`:
```python
CATEGORIES = {
    "web-apps": ["laravel", "nextjs", "vue"],
    "scripts": ["bash", "python"],
    "photography": ["gallery", "image", "lightroom"],  # Custom
    "client-work": ["client", "booking"],              # Custom
}
```

### Notes.test Configuration

**Environment (.env):**
```env
APP_NAME="Project Organizer Dashboard"
APP_URL=https://notes.test

# Optional: API authentication
PROJECTS_API_TOKEN=your-secret-token
```

**Customize tags, statuses, and filters** in Filament resources.

## 📱 Screenshots

### Notes.test Dashboard
*(Where you'd see screenshots of)*
- Projects list view with tags
- Project detail page with metadata
- Search interface
- Tag management
- Status filtering

## 🤝 Contributing

This tool was designed for photographers and developers who need to organize scattered projects. Contributions welcome!

**Ideas for enhancements:**
- Support for more project types
- Custom categorization rules
- Advanced Notes.test integrations
- Photography-specific features (Lightroom catalog detection, etc.)
- Team collaboration features
- Project dependencies tracking

## 📚 Documentation

### Scanner Documentation
- [QUICKSTART.md](QUICKSTART.md) - 5-minute quick start guide
- [USAGE.md](USAGE.md) - Detailed command reference
- [SINGLE_PROJECT_SUPPORT.md](SINGLE_PROJECT_SUPPORT.md) - Single project scanning
- [HANDLING_LARGE_DIRS.md](HANDLING_LARGE_DIRS.md) - Dealing with large directories
- [EXAMPLE_OUTPUT.md](EXAMPLE_OUTPUT.md) - Example proposal output

### Notes.test Documentation
- Installation and setup guide
- API integration documentation
- Customization guide
- Tag management
- Search and filtering

## 🔒 Security & Privacy

- **Local processing**: All scanning happens on your machine
- **No file uploads**: Only metadata is stored
- **API key security**: Gemini API key stored locally
- **No cloud storage**: Projects stay on your machine
- **Git awareness**: Preserves remotes, warns about uncommitted changes

## 🐛 Troubleshooting

### Scanner Issues

**"Command not found"**
```bash
export PATH="$HOME/.local/bin:$PATH"
```

**"JSON parsing error"**
```bash
# Remove corrupted data and rescan
rm ~/.project_data/projects.json
project-organizer ~/Herd
```

**"No projects found"**
```bash
# Increase depth or check directory
MAX_DEPTH=6 project-organizer ~/Code
```

### Notes.test Issues

**"Projects not showing"**
- Check database connection
- Verify import/sync completed
- Clear cache: `php artisan cache:clear`

**"Search not working"**
- Rebuild search index
- Check Filament configuration

## 💡 Pro Tips

### For Photographers

1. **Tag by shoot type**: "weddings", "portraits", "commercial"
2. **Track revenue**: Flag client-facing projects as "revenue-generating"
3. **Archive old work**: Mark completed projects as "archived"
4. **Quick access**: Search for projects when clients call
5. **Regular scans**: Rescan monthly to keep metadata fresh

### For Developers

1. **Tag by technology**: Automatically tagged during scan
2. **Tag by team**: Add team ownership tags
3. **Track dependencies**: Link related projects
4. **Monitor activity**: Check last_modified to find stale projects
5. **Git integration**: Quickly see uncommitted changes

## 📝 License

MIT License - feel free to use, modify, and distribute.

## 🙏 Credits

**Built with:**
- Google Gemini API for AI analysis
- Laravel 11 & Filament 4.2 for dashboard
- `tree` for directory visualization
- `jq` for JSON processing
- Python `requests` for API calls

**Created for:**
Photographers and developers who are drowning in disorganized code projects and need a metadata-based, search-friendly organization system.

## 🚀 Get Started Now

```bash
# 1. Install scanner
cd ~/project-organizer
./setup.sh

# 2. Set up Notes.test
cd ~/Herd/Notes.test
composer install && php artisan migrate

# 3. Scan your projects
project-organizer ~/Herd

# 4. Import to dashboard
# (Use your preferred integration method)

# 5. Enjoy organized project management! 🎉
```

---

**Your scattered projects → AI-organized metadata → Beautiful dashboard** 

*No file moving. No destructive changes. Just intelligent organization.*

📸 Perfect for photography studios. 💻 Essential for busy developers.
