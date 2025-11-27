#!/usr/bin/env bash

# PROJECT ORGANIZER - Pre-Scanner
# Health check before full scan - detects problematic directories
# Helps identify issues with excessive files, corrupted caches, etc.

set -euo pipefail

# Colors
readonly RED='\033[0;31m'
readonly GREEN='\033[0;32m'
readonly YELLOW='\033[1;33m'
readonly BLUE='\033[0;34m'
readonly NC='\033[0m'

# Thresholds
readonly WARN_FILE_COUNT=10000
readonly PROBLEM_FILE_COUNT=50000

# Auto-exclude patterns (same as main scanner)
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

# Scan root
SCAN_ROOT="${1:-.}"
MAX_DEPTH="${MAX_DEPTH:-4}"

log_info() {
    echo -e "${BLUE}[INFO]${NC} $*"
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $*"
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $*"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $*"
}

# Build find exclusion arguments
build_exclude_args() {
    local args=()
    for pattern in "${EXCLUDE_PATTERNS[@]}"; do
        args+=("-path" "*/${pattern}" "-prune" "-o")
    done
    echo "${args[@]}"
}

# Count files in a directory (non-recursively for speed)
count_files_fast() {
    local dir="$1"
    find "$dir" -maxdepth 1 -type f 2>/dev/null | wc -l | tr -d ' '
}

# Get sample of filenames
get_sample_files() {
    local dir="$1"
    find "$dir" -maxdepth 2 -type f 2>/dev/null | head -20
}

# Check if directory might be a cache
is_likely_cache() {
    local dir="$1"
    local dirname
    dirname=$(basename "$dir")
    
    case "$dirname" in
        *cache*|*tmp*|*temp*|.next|.nuxt|__pycache__|.pytest_cache|.mypy_cache)
            return 0
            ;;
        *)
            return 1
            ;;
    esac
}

# Scan directories for issues
scan_for_issues() {
    local scan_root="$1"
    local exclude_args
    exclude_args=($(build_exclude_args))
    
    log_info "Pre-scanning: ${scan_root}"
    log_info "This may take a minute..."
    echo
    
    local issues_found=false
    local warning_dirs=()
    local problem_dirs=()
    
    # Find all directories up to MAX_DEPTH
    while IFS= read -r dir; do
        local file_count
        file_count=$(count_files_fast "$dir")
        
        if [ "$file_count" -ge "$PROBLEM_FILE_COUNT" ]; then
            problem_dirs+=("$dir:$file_count")
            issues_found=true
        elif [ "$file_count" -ge "$WARN_FILE_COUNT" ]; then
            warning_dirs+=("$dir:$file_count")
            issues_found=true
        fi
        
    done < <(find "$scan_root" -maxdepth "$MAX_DEPTH" \
        "${exclude_args[@]}" \
        -type d -print 2>/dev/null)
    
    # Report findings
    if [ "$issues_found" = false ]; then
        log_success "No issues detected!"
        log_info "Your directory structure looks clean."
        echo
        return 0
    fi
    
    # Show warnings
    if [ ${#warning_dirs[@]} -gt 0 ]; then
        log_warning "Directories with ${WARN_FILE_COUNT}+ files:"
        echo
        for entry in "${warning_dirs[@]}"; do
            local dir="${entry%:*}"
            local count="${entry#*:}"
            echo -e "  ${YELLOW}⚠${NC} $(printf '%-60s' "$dir") ${count} files"
            
            if is_likely_cache "$dir"; then
                echo -e "     ${BLUE}ℹ${NC} This looks like a cache directory"
            fi
        done
        echo
    fi
    
    # Show problems
    if [ ${#problem_dirs[@]} -gt 0 ]; then
        log_error "Directories with ${PROBLEM_FILE_COUNT}+ files (PROBLEMATIC):"
        echo
        for entry in "${problem_dirs[@]}"; do
            local dir="${entry%:*}"
            local count="${entry#*:}"
            echo -e "  ${RED}✗${NC} $(printf '%-60s' "$dir") ${count} files"
            
            if is_likely_cache "$dir"; then
                echo -e "     ${BLUE}ℹ${NC} This looks like a cache directory"
            fi
            
            # Show sample files
            echo -e "     ${BLUE}Sample files:${NC}"
            get_sample_files "$dir" | head -5 | while IFS= read -r file; do
                echo "       - $(basename "$file")"
            done
        done
        echo
    fi
    
    # Recommendations
    log_info "Recommendations:"
    echo
    
    if [ ${#problem_dirs[@]} -gt 0 ]; then
        echo "  1. Review the directories listed above"
        echo "  2. Consider cleaning up cache/temp directories:"
        echo
        for entry in "${problem_dirs[@]}"; do
            local dir="${entry%:*}"
            if is_likely_cache "$dir"; then
                echo "     rm -rf \"$dir\""
            fi
        done
        echo
    fi
    
    echo "  3. Add problematic directories to exclude list in project-organizer.sh"
    echo "  4. After cleanup, re-run this pre-scan"
    echo
    
    # Font cache specific detection
    for entry in "${problem_dirs[@]}" "${warning_dirs[@]}"; do
        local dir="${entry%:*}"
        if [[ "$dir" == *".cache/fontconfig"* ]] || [[ "$dir" == *"font"* ]]; then
            log_info "Font cache detected!"
            echo "  To clean font caches:"
            echo "    macOS: sudo atsutil databases -remove"
            echo "    Linux: fc-cache -f -v"
            echo
            break
        fi
    done
    
    return 1
}

# Generate exclusion list
suggest_exclusions() {
    log_info "To exclude these directories, add them to EXCLUDE_PATTERNS in project-organizer.sh:"
    echo
    echo "EXCLUDE_PATTERNS+=("
    for entry in "$@"; do
        local dir="${entry%:*}"
        local dirname
        dirname=$(basename "$dir")
        echo "    \"$dirname\""
    done
    echo ")"
    echo
}

main() {
    log_info "Project Organizer - Pre-Scanner"
    log_info "========================================"
    echo
    
    # Resolve scan root
    SCAN_ROOT=$(cd "$SCAN_ROOT" && pwd)
    
    # Run scan
    if scan_for_issues "$SCAN_ROOT"; then
        log_success "Pre-scan complete - ready for full scan!"
        log_info "Next step: project-organizer ${SCAN_ROOT}"
    else
        log_warning "Issues detected - review and clean up before running full scan"
        log_info "After cleanup, re-run: project-prescan ${SCAN_ROOT}"
    fi
    
    echo
}

main "$@"
