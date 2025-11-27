#!/usr/bin/env bash

# PROJECT ORGANIZER - Main Scanner Script
# Recursively scans directories for projects and collects metadata
# Output: JSON database at ~/.project_data/projects.json

set -euo pipefail

# Colors for output
readonly RED='\033[0;31m'
readonly GREEN='\033[0;32m'
readonly YELLOW='\033[1;33m'
readonly BLUE='\033[0;34m'
readonly NC='\033[0m' # No Color

# Configuration
SCAN_ROOT="${1:-.}"
MAX_DEPTH="${MAX_DEPTH:-4}"
PROJECT_DATA_DIR="${HOME}/.project_data"
PROJECT_DATA_FILE="${PROJECT_DATA_DIR}/projects.json"

# Auto-exclude patterns
EXCLUDE_PATTERNS=(
    "node_modules"
    "vendor"
    ".git"
    "__pycache__"
    "dist"
    "build"
    ".next"
    ".nuxt"
    "venv"
    ".venv"
    ".cache"
    "target"
    ".bundle"
)

# Project markers - files/folders that indicate a project root
PROJECT_MARKERS=(
    ".git"
    "package.json"
    "composer.json"
    "requirements.txt"
    "Cargo.toml"
    "go.mod"
    "Gemfile"
    "pyproject.toml"
    "Dockerfile"
)

# Utility functions
log_info() {
    echo -e "${BLUE}[INFO]${NC} $*" >&2
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $*" >&2
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $*" >&2
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $*" >&2
}

# Check dependencies
check_dependencies() {
    local missing_deps=()
    
    for cmd in tree jq git python3; do
        if ! command -v "$cmd" &> /dev/null; then
            missing_deps+=("$cmd")
        fi
    done
    
    if [ ${#missing_deps[@]} -gt 0 ]; then
        log_error "Missing required dependencies: ${missing_deps[*]}"
        log_info "Install with: brew install tree jq git python3 (macOS)"
        log_info "           or: apt-get install tree jq git python3 (Linux)"
        exit 1
    fi
}

# Build find exclusion arguments
build_exclude_args() {
    local args=()
    for pattern in "${EXCLUDE_PATTERNS[@]}"; do
        args+=("-path" "*/${pattern}" "-prune" "-o")
    done
    echo "${args[@]}"
}

# Find all project directories
find_projects() {
    local scan_root="$1"
    local max_depth="$2"
    
    log_info "Scanning for projects in: ${scan_root}"
    log_info "Max depth: ${max_depth}"
    
    local exclude_args
    exclude_args=($(build_exclude_args))
    
    # Build find command for each marker
    local project_dirs=()
    for marker in "${PROJECT_MARKERS[@]}"; do
        while IFS= read -r dir; do
            # Get the parent directory (the project root)
            local project_dir
            project_dir="$(dirname "$dir")"
            
            # Avoid duplicates
            if [[ ! " ${project_dirs[*]} " =~ ${project_dir} ]]; then
                project_dirs+=("$project_dir")
            fi
        done < <(find "$scan_root" -maxdepth "$max_depth" \
            "${exclude_args[@]}" \
            -name "$marker" -print 2>/dev/null)
    done
    
    printf '%s\n' "${project_dirs[@]}" | sort -u
}

# Detect project types based on files present
detect_project_types() {
    local project_dir="$1"
    local types=()
    
    # Laravel
    [[ -f "$project_dir/artisan" ]] && types+=("laravel")
    
    # Next.js
    [[ -f "$project_dir/next.config.js" ]] || [[ -f "$project_dir/next.config.mjs" ]] && types+=("nextjs")
    
    # Vue
    [[ -f "$project_dir/vue.config.js" ]] || grep -q "\"vue\"" "$project_dir/package.json" 2>/dev/null && types+=("vue")
    
    # Angular
    [[ -f "$project_dir/angular.json" ]] && types+=("angular")
    
    # React
    grep -q "\"react\"" "$project_dir/package.json" 2>/dev/null && types+=("react")
    
    # Node.js
    [[ -f "$project_dir/package.json" ]] && types+=("node")
    
    # PHP
    [[ -f "$project_dir/composer.json" ]] && types+=("php")
    
    # Python
    [[ -f "$project_dir/requirements.txt" ]] || [[ -f "$project_dir/pyproject.toml" ]] && types+=("python")
    
    # Bash
    find "$project_dir" -maxdepth 2 -name "*.sh" -type f 2>/dev/null | grep -q . && types+=("bash")
    
    # Docker
    [[ -f "$project_dir/Dockerfile" ]] || [[ -f "$project_dir/docker-compose.yml" ]] && types+=("docker")
    
    # Rust
    [[ -f "$project_dir/Cargo.toml" ]] && types+=("rust")
    
    # Go
    [[ -f "$project_dir/go.mod" ]] && types+=("go")
    
    # Ruby
    [[ -f "$project_dir/Gemfile" ]] && types+=("ruby")
    
    # Join types with commas
    local IFS=','
    echo "${types[*]}"
}

# Get git information
get_git_info() {
    local project_dir="$1"
    
    if [[ ! -d "$project_dir/.git" ]]; then
        echo "{}"
        return
    fi
    
    cd "$project_dir" || return
    
    local branch remote has_changes
    branch=$(git rev-parse --abbrev-ref HEAD 2>/dev/null || echo "unknown")
    remote=$(git remote get-url origin 2>/dev/null || echo "none")
    
    # Check for uncommitted changes
    if [[ -n $(git status --porcelain 2>/dev/null) ]]; then
        has_changes="true"
    else
        has_changes="false"
    fi
    
    cat <<EOF
{
  "branch": "$branch",
  "remote": "$remote",
  "has_uncommitted_changes": $has_changes
}
EOF
}

# Read documentation
get_documentation() {
    local project_dir="$1"
    local doc_text=""
    
    # Try README files
    for readme in README.md readme.md README.txt README; do
        if [[ -f "$project_dir/$readme" ]]; then
            doc_text=$(head -c 1000 "$project_dir/$readme" | jq -Rs .)
            break
        fi
    done
    
    # If no README, try docs folder
    if [[ -z "$doc_text" ]] && [[ -d "$project_dir/docs" ]]; then
        local first_doc
        first_doc=$(find "$project_dir/docs" -type f -name "*.md" | head -1)
        if [[ -n "$first_doc" ]]; then
            doc_text=$(head -c 1000 "$first_doc" | jq -Rs .)
        fi
    fi
    
    # Return empty string if nothing found
    if [[ -z "$doc_text" ]]; then
        doc_text='""'
    fi
    
    echo "$doc_text"
}

# Generate tree structure
get_tree_structure() {
    local project_dir="$1"
    
    # Use tree with gitignore support if available
    if tree --help 2>&1 | grep -q -- '--gitignore'; then
        tree --gitignore --dirsfirst -F -L 2 -a "$project_dir" 2>/dev/null | head -100 | jq -Rs .
    else
        tree --dirsfirst -F -L 2 -a -I "node_modules|vendor|.git|__pycache__|dist|build" "$project_dir" 2>/dev/null | head -100 | jq -Rs .
    fi
}

# Get file count
get_file_count() {
    local project_dir="$1"
    find "$project_dir" -type f 2>/dev/null | wc -l | tr -d ' '
}

# Get directory size (platform-aware)
get_dir_size() {
    local project_dir="$1"
    
    if [[ "$OSTYPE" == "darwin"* ]]; then
        # macOS
        du -sh "$project_dir" 2>/dev/null | cut -f1
    else
        # Linux
        du -sh "$project_dir" 2>/dev/null | cut -f1
    fi
}

# Get last modified date
get_last_modified() {
    local project_dir="$1"
    
    if [[ "$OSTYPE" == "darwin"* ]]; then
        # macOS
        stat -f "%Sm" -t "%Y-%m-%d" "$project_dir" 2>/dev/null
    else
        # Linux
        stat -c "%y" "$project_dir" 2>/dev/null | cut -d' ' -f1
    fi
}

# Analyze a single project
analyze_project() {
    local project_dir="$1"
    local project_name
    project_name="$(basename "$project_dir")"
    
    log_info "Analyzing: ${project_name}"
    
    local types git_info doc tree_str file_count size last_mod
    
    types=$(detect_project_types "$project_dir")
    git_info=$(get_git_info "$project_dir")
    doc=$(get_documentation "$project_dir")
    tree_str=$(get_tree_structure "$project_dir")
    file_count=$(get_file_count "$project_dir")
    size=$(get_dir_size "$project_dir")
    last_mod=$(get_last_modified "$project_dir")
    
    # Build JSON object using jq -n for proper escaping (no trailing newlines)
    cat <<EOF
{
  "name": $(printf '%s' "$project_name" | jq -Rs .),
  "original_path": $(printf '%s' "$project_dir" | jq -Rs .),
  "types": $(printf '%s' "$types" | jq -Rs .),
  "file_count": $file_count,
  "size": $(printf '%s' "$size" | jq -Rs .),
  "last_modified": $(printf '%s' "$last_mod" | jq -Rs .),
  "tree_structure": $tree_str,
  "documentation": $doc,
  "git": $git_info,
  "analyzed_at": "$(date -u +"%Y-%m-%dT%H:%M:%SZ")"
}
EOF
}

# Main function
main() {
    log_info "Project Organizer - Scanner"
    log_info "========================================"
    
    # Check dependencies
    check_dependencies
    
    # Create data directory
    mkdir -p "$PROJECT_DATA_DIR"
    
    # Resolve scan root
    SCAN_ROOT=$(cd "$SCAN_ROOT" && pwd)
    
    log_info "Scanning root: ${SCAN_ROOT}"
    
    # Find all projects
    local projects
    mapfile -t projects < <(find_projects "$SCAN_ROOT" "$MAX_DEPTH")
    
    if [ ${#projects[@]} -eq 0 ]; then
        log_warning "No projects found in ${SCAN_ROOT}"
        exit 0
    fi
    
    log_success "Found ${#projects[@]} projects"
    
    # Analyze each project and build JSON array
    echo "[" > "$PROJECT_DATA_FILE"
    
    local first=true
    for project_dir in "${projects[@]}"; do
        if [ "$first" = true ]; then
            first=false
        else
            echo "," >> "$PROJECT_DATA_FILE"
        fi
        
        analyze_project "$project_dir" >> "$PROJECT_DATA_FILE"
    done
    
    echo "" >> "$PROJECT_DATA_FILE"
    echo "]" >> "$PROJECT_DATA_FILE"
    
    log_success "Analysis complete!"
    log_info "Data saved to: ${PROJECT_DATA_FILE}"
    log_info "Total projects: ${#projects[@]}"
    log_info ""
    log_info "Next steps:"
    log_info "  1. Review the data: cat ${PROJECT_DATA_FILE} | jq '.'"
    log_info "  2. Run AI analysis: project-organizer-ai.py"
    log_info "  3. Review proposal: cat ~/.project_data/organization_proposal.md"
}

# Run main
main "$@"
