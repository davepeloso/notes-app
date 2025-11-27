#!/usr/bin/env python3

"""
PROJECT ANALYZER - AI Analysis Script (Enhanced for Notes.test)
Uses Google Gemini API to analyze projects and generate data for Notes.test sync
"""

import json
import os
import sys
import re
from datetime import datetime
from typing import List, Dict, Any
import requests

# Configuration
PROJECT_DATA_DIR = os.path.expanduser("~/.project_data")
PROJECT_DATA_FILE = os.path.join(PROJECT_DATA_DIR, "projects.json")
PROPOSAL_FILE = os.path.join(PROJECT_DATA_DIR, "organization_proposal.md")
SYNC_DATA_FILE = os.path.join(PROJECT_DATA_DIR, "notes_sync_data.json")

# Gemini API Configuration
GEMINI_API_KEY = os.environ.get("GEMINI_API_KEY")
GEMINI_API_URL = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent"

# Batch size for API calls
BATCH_SIZE = 5

# Tech stack color mappings
TECH_COLORS = {
    "laravel": "#f05340",
    "php": "#777bb4",
    "vue": "#42b883",
    "react": "#61dafb",
    "nextjs": "#000000",
    "angular": "#dd0031",
    "python": "#3776ab",
    "node": "#339933",
    "bash": "#4eaa25",
    "docker": "#2496ed",
    "go": "#00add8",
    "rust": "#ce422b",
    "ruby": "#cc342d",
}

# Flag colors
FLAG_COLORS = {
    "revenue-generating": "#fbbf24",
    "client-facing": "#8b5cf6",
    "needs-attention": "#f97316",
    "high-priority": "#ef4444",
    "production": "#10b981",
    "maintenance": "#6b7280",
    "experimental": "#06b6d4",
    "deprecated": "#991b1b",
}

# ANSI color codes
RED = '\033[0;31m'
GREEN = '\033[0;32m'
YELLOW = '\033[1;33m'
BLUE = '\033[0;34m'
NC = '\033[0m'


def log_info(msg: str):
    print(f"{BLUE}[INFO]{NC} {msg}")


def log_success(msg: str):
    print(f"{GREEN}[SUCCESS]{NC} {msg}")


def log_warning(msg: str):
    print(f"{YELLOW}[WARNING]{NC} {msg}")


def log_error(msg: str):
    print(f"{RED}[ERROR]{NC} {msg}", file=sys.stderr)


def check_api_key():
    """Check if Gemini API key is set"""
    if not GEMINI_API_KEY:
        log_error("GEMINI_API_KEY environment variable not set")
        log_info("Get your API key from: https://makersuite.google.com/app/apikey")
        log_info("Then run: export GEMINI_API_KEY='your-key-here'")
        sys.exit(1)


def load_projects() -> List[Dict[str, Any]]:
    """Load project data from JSON file"""
    if not os.path.exists(PROJECT_DATA_FILE):
        log_error(f"Project data file not found: {PROJECT_DATA_FILE}")
        log_info("Run project-organizer.sh first to scan projects")
        sys.exit(1)
    
    with open(PROJECT_DATA_FILE, 'r') as f:
        return json.load(f)


def prepare_project_summary(project: Dict[str, Any]) -> Dict[str, Any]:
    """Prepare a summary of project for AI analysis"""
    return {
        "name": project.get("name", "unknown"),
        "types": project.get("types", ""),
        "documentation": project.get("documentation", "")[:500],
        "tree_structure": project.get("tree_structure", "")[:500],
        "file_count": project.get("file_count", 0),
        "git_remote": project.get("git", {}).get("remote", "none")
    }


def call_gemini_api(projects_batch: List[Dict[str, Any]]) -> List[Dict[str, Any]]:
    """Call Gemini API to analyze a batch of projects"""
    
    summaries = [prepare_project_summary(p) for p in projects_batch]
    
    prompt = f"""You are a project intelligence analyst for a photography business owner who codes. Analyze these {len(summaries)} projects and provide detailed insights.

For each project, determine:
1. **suggested_name**: A clear, descriptive name (lowercase-with-dashes)
2. **category**: web-apps, scripts, infrastructure, libraries, documentation, or research
3. **business_context**: client-facing, internal-tool, automation, or research
4. **description**: 2-3 sentences explaining what it does and its business value
5. **tech_stack**: List of primary technologies used
6. **status_flags**: Array of status indicators:
   - revenue-generating (if it makes money)
   - client-facing (if customers use it)
   - needs-attention (if has tech debt or issues)
   - high-priority (if critical to business)
   - production (if in active use)
   - maintenance (if stable, just needs occasional updates)
   - experimental (if prototype/learning)
   - deprecated (if no longer used)
7. **confidence**: high, medium, or low

Projects to analyze:
{json.dumps(summaries, indent=2)}

Return ONLY a valid JSON array with this structure (no markdown, no code blocks):
[
  {{
    "suggested_name": "photography-gallery",
    "category": "web-apps",
    "business_context": "client-facing",
    "description": "Client gallery system for delivering photos with Stripe payments",
    "tech_stack": ["laravel", "vue", "mysql"],
    "status_flags": ["revenue-generating", "production", "client-facing"],
    "confidence": "high"
  }}
]"""

    headers = {"Content-Type": "application/json"}
    
    payload = {
        "contents": [{
            "parts": [{"text": prompt}]
        }],
        "generationConfig": {
            "temperature": 0.2,
            "topK": 40,
            "topP": 0.95,
            "maxOutputTokens": 2048,
        }
    }
    
    try:
        response = requests.post(
            f"{GEMINI_API_URL}?key={GEMINI_API_KEY}",
            headers=headers,
            json=payload,
            timeout=30
        )
        
        response.raise_for_status()
        data = response.json()
        
        if 'candidates' in data and len(data['candidates']) > 0:
            text = data['candidates'][0]['content']['parts'][0]['text']
            
            # Extract JSON from markdown code blocks
            json_match = re.search(r'```(?:json)?\s*(\[.*?\])\s*```', text, re.DOTALL)
            if json_match:
                text = json_match.group(1)
            
            return json.loads(text)
        else:
            log_warning("Unexpected API response format")
            return []
            
    except Exception as e:
        log_error(f"API request failed: {e}")
        return []


def analyze_projects_with_ai(projects: List[Dict[str, Any]]) -> List[Dict[str, Any]]:
    """Analyze all projects using AI in batches"""
    results = []
    
    log_info(f"Analyzing {len(projects)} projects with AI...")
    log_info(f"Batch size: {BATCH_SIZE} projects per API call")
    
    for i in range(0, len(projects), BATCH_SIZE):
        batch = projects[i:i + BATCH_SIZE]
        batch_num = (i // BATCH_SIZE) + 1
        total_batches = (len(projects) + BATCH_SIZE - 1) // BATCH_SIZE
        
        log_info(f"Processing batch {batch_num}/{total_batches}...")
        
        ai_results = call_gemini_api(batch)
        
        # Merge AI results with original project data
        for j, project in enumerate(batch):
            if j < len(ai_results):
                project["ai_analysis"] = ai_results[j]
            else:
                # Fallback
                project["ai_analysis"] = {
                    "suggested_name": project["name"],
                    "category": "uncategorized",
                    "business_context": "unknown",
                    "description": "Automatically categorized",
                    "tech_stack": project.get("types", "").split(","),
                    "status_flags": [],
                    "confidence": "low"
                }
            results.append(project)
    
    return results


def generate_notes_sync_data(projects: List[Dict[str, Any]]):
    """Generate JSON data formatted for Notes.test API sync"""
    
    sync_data = {
        "generated_at": datetime.now().isoformat(),
        "total_projects": len(projects),
        "projects": []
    }
    
    for project in projects:
        ai = project.get("ai_analysis", {})
        git = project.get("git", {})
        
        # Build markdown content
        markdown_content = f"""# {ai.get('suggested_name', project['name'])}

## Purpose
{ai.get('description', 'No description available')}

## Business Context
**Type**: {ai.get('business_context', 'unknown')}
**Category**: {ai.get('category', 'uncategorized')}

## Original Location
`{project.get('original_path', 'unknown')}`

## Project Metrics
- **Files**: {project.get('file_count', 0):,}
- **Size**: {project.get('size', 'unknown')}
- **Last Modified**: {project.get('last_modified', 'unknown')}

## Tech Stack
{', '.join(ai.get('tech_stack', []))}

## Git Information
"""
        
        if git.get('remote') != 'none' and git.get('remote'):
            markdown_content += f"""- **Remote**: {git.get('remote')}
- **Branch**: {git.get('branch', 'unknown')}
- **Has uncommitted changes**: {'Yes' if git.get('has_uncommitted_changes') else 'No'}
"""
        else:
            markdown_content += "- No git repository\n"
        
        # Add documentation excerpt if available
        if project.get('documentation'):
            markdown_content += f"""
## Documentation Excerpt
```
{project['documentation'][:500]}...
```
"""
        
        # Build JSON metadata for code_content field
        json_metadata = {
            "original_path": project.get("original_path"),
            "analyzed_at": project.get("analyzed_at"),
            "tech_stack": {
                "detected_types": project.get("types", "").split(","),
                "ai_identified": ai.get("tech_stack", [])
            },
            "git": git,
            "metrics": {
                "file_count": project.get("file_count", 0),
                "size": project.get("size"),
                "last_modified": project.get("last_modified")
            },
            "tree_structure": project.get("tree_structure", "")[:1000],
            "ai_confidence": ai.get("confidence", "low")
        }
        
        # Create tags from tech stack
        tags = []
        for tech in ai.get("tech_stack", []):
            if tech:
                tags.append({
                    "name": tech.lower(),
                    "color": TECH_COLORS.get(tech.lower(), "#6b7280"),
                    "is_flag": False
                })
        
        # Add business context as tag
        if ai.get("business_context"):
            tags.append({
                "name": ai.get("business_context"),
                "color": "#8b5cf6",
                "is_flag": False
            })
        
        # Add category as tag
        if ai.get("category"):
            tags.append({
                "name": ai.get("category"),
                "color": "#3b82f6",
                "is_flag": False
            })
        
        # Create flags from status_flags
        flags = []
        for flag in ai.get("status_flags", []):
            if flag:
                flags.append({
                    "name": flag,
                    "color": FLAG_COLORS.get(flag, "#6b7280"),
                    "is_flag": True
                })
        
        # Build project sync data
        project_sync = {
            "project_data": {
                "name": ai.get("suggested_name", project["name"]),
                "description": ai.get("description", "")[:500],
                "color": TECH_COLORS.get(ai.get("tech_stack", [""])[0], "#3b82f6")
            },
            "note_data": {
                "title": f"{ai.get('suggested_name', project['name'])} [Analysis]",
                "type": "mixed",
                "content": markdown_content,
                "code_content": json.dumps(json_metadata, indent=2)
            },
            "tags": tags,
            "flags": flags
        }
        
        sync_data["projects"].append(project_sync)
    
    # Save sync data
    with open(SYNC_DATA_FILE, 'w') as f:
        json.dump(sync_data, f, indent=2)
    
    log_success(f"Sync data generated: {SYNC_DATA_FILE}")
    return sync_data


def generate_proposal(projects: List[Dict[str, Any]]):
    """Generate markdown proposal document (same as before, for human review)"""
    
    # Group by category
    by_category = {}
    for project in projects:
        category = project["ai_analysis"]["category"]
        if category not in by_category:
            by_category[category] = []
        by_category[category].append(project)
    
    with open(PROPOSAL_FILE, 'w') as f:
        f.write("# Project Intelligence Report\n\n")
        f.write(f"Generated: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n")
        f.write(f"Total projects: {len(projects)}\n\n")
        
        f.write("## Summary by Category\n\n")
        for category in sorted(by_category.keys()):
            count = len(by_category[category])
            f.write(f"- **{category}**: {count} projects\n")
        
        # Detailed listings
        for category in sorted(by_category.keys()):
            f.write(f"\n## {category.upper()}\n\n")
            
            for project in sorted(by_category[category], key=lambda p: p["ai_analysis"]["suggested_name"]):
                ai = project["ai_analysis"]
                git = project.get("git", {})
                
                f.write(f"### {ai['suggested_name']}\n\n")
                f.write(f"**Original**: {project['name']}\n")
                f.write(f"**Path**: `{project['original_path']}`\n")
                f.write(f"**Business Context**: {ai.get('business_context', 'unknown')}\n")
                f.write(f"**Tech Stack**: {', '.join(ai.get('tech_stack', []))}\n")
                
                if ai.get('status_flags'):
                    f.write(f"**Flags**: {', '.join(ai['status_flags'])}\n")
                
                f.write(f"\n{ai['description']}\n\n")
                
                if git.get('remote') != 'none':
                    f.write(f"**Git**: {git.get('remote')}\n")
                
                f.write("---\n\n")
    
    log_success(f"Proposal generated: {PROPOSAL_FILE}")


def main():
    """Main function"""
    log_info("Project Analyzer - Enhanced for Notes.test")
    log_info("============================================")
    
    # Check API key
    check_api_key()
    
    # Load projects
    projects = load_projects()
    log_success(f"Loaded {len(projects)} projects")
    
    # Analyze with AI
    analyzed_projects = analyze_projects_with_ai(projects)
    
    # Generate outputs
    generate_proposal(analyzed_projects)
    generate_notes_sync_data(analyzed_projects)
    
    log_success("Analysis complete!")
    log_info("")
    log_info("Next steps:")
    log_info(f"  1. Review proposal: cat {PROPOSAL_FILE}")
    log_info(f"  2. Review sync data: cat {SYNC_DATA_FILE}")
    log_info(f"  3. Sync to Notes.test: sync-to-notes")


if __name__ == "__main__":
    main()
