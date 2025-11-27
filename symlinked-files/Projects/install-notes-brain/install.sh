#!/usr/bin/env bash

# PROJECT ANALYZER → NOTES.TEST INTEGRATION
# Quick installation script

set -euo pipefail

# Colors
readonly RED='\033[0;31m'
readonly GREEN='\033[0;32m'
readonly YELLOW='\033[1;33m'
readonly BLUE='\033[0;34m'
readonly CYAN='\033[0;36m'
readonly BOLD='\033[1m'
readonly NC='\033[0m'

log_info() { echo -e "${BLUE}[INFO]${NC} $*"; }
log_success() { echo -e "${GREEN}[SUCCESS]${NC} $*"; }
log_warning() { echo -e "${YELLOW}[WARNING]${NC} $*"; }
log_error() { echo -e "${RED}[ERROR]${NC} $*"; }
log_step() { echo -e "${CYAN}▸${NC} $*"; }

# Configuration
INTEGRATION_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
NOTES_APP_PATH="${NOTES_APP_PATH:-/Users/davepeloso/Herd/notes}"
LOCAL_BIN="${HOME}/.local/bin"

echo
log_info "${BOLD}Project Analyzer → Notes.test Integration${NC}"
log_info "=========================================="
echo

# Check if Notes app exists
if [ ! -d "$NOTES_APP_PATH" ]; then
    log_error "Notes.test app not found at: $NOTES_APP_PATH"
    echo
    log_info "Please set the correct path:"
    echo "  export NOTES_APP_PATH='/path/to/notes'"
    echo "  ./install.sh"
    exit 1
fi

log_success "Found Notes.test at: $NOTES_APP_PATH"
echo

# Step 1: Install Laravel API Controller
log_step "Installing API Controller..."
API_CONTROLLER_DIR="$NOTES_APP_PATH/app/Http/Controllers/Api"
mkdir -p "$API_CONTROLLER_DIR"

if [ -f "$INTEGRATION_DIR/ProjectSyncController.php" ]; then
    cp "$INTEGRATION_DIR/ProjectSyncController.php" "$API_CONTROLLER_DIR/"
    log_success "API Controller installed"
else
    log_error "ProjectSyncController.php not found in $INTEGRATION_DIR"
    exit 1
fi

echo

# Step 2: Add API Routes
log_step "Checking API routes..."
if grep -q "ProjectSyncController" "$NOTES_APP_PATH/routes/api.php" 2>/dev/null; then
    log_success "API routes already added"
else
    log_warning "API routes not found"
    log_info "Please manually add routes to: $NOTES_APP_PATH/routes/api.php"
    log_info "See: $INTEGRATION_DIR/api-routes.php"
fi

echo

# Step 3: Install Python scripts
log_step "Installing Python scripts..."
mkdir -p "$LOCAL_BIN"

# Install enhanced analyzer
if [ -f "$INTEGRATION_DIR/project-organizer-ai.py" ]; then
    cp "$INTEGRATION_DIR/project-organizer-ai.py" "$LOCAL_BIN/"
    chmod +x "$LOCAL_BIN/project-organizer-ai.py"
    ln -sf "$LOCAL_BIN/project-organizer-ai.py" "$LOCAL_BIN/project-organizer-ai"
    log_success "Enhanced analyzer installed"
else
    log_error "project-organizer-ai.py not found"
    exit 1
fi

# Install sync script
if [ -f "$INTEGRATION_DIR/sync-to-notes.py" ]; then
    cp "$INTEGRATION_DIR/sync-to-notes.py" "$LOCAL_BIN/"
    chmod +x "$LOCAL_BIN/sync-to-notes.py"
    ln -sf "$LOCAL_BIN/sync-to-notes.py" "$LOCAL_BIN/sync-to-notes"
    log_success "Sync script installed"
else
    log_error "sync-to-notes.py not found"
    exit 1
fi

echo

# Step 4: Check environment
log_step "Checking environment..."

if [ -z "${GEMINI_API_KEY:-}" ]; then
    log_warning "GEMINI_API_KEY not set"
    log_info "Set it with: export GEMINI_API_KEY='your-key'"
else
    log_success "GEMINI_API_KEY is set"
fi

if [ -z "${NOTES_API_URL:-}" ]; then
    log_info "NOTES_API_URL not set (using default: http://notes.test/api)"
    log_info "To change: export NOTES_API_URL='http://localhost:8000/api'"
else
    log_success "NOTES_API_URL set to: $NOTES_API_URL"
fi

echo

# Step 5: Test Laravel API
log_step "Testing Laravel API..."
cd "$NOTES_APP_PATH"

# Check if Laravel is running
if curl -s http://notes.test/api/sync/stats > /dev/null 2>&1; then
    log_success "Laravel API is accessible at http://notes.test/api"
elif curl -s http://localhost:8000/api/sync/stats > /dev/null 2>&1; then
    log_success "Laravel API is accessible at http://localhost:8000/api"
else
    log_warning "Laravel API not responding"
    log_info "Start Laravel with:"
    echo "  cd $NOTES_APP_PATH"
    echo "  php artisan serve"
fi

echo

# Success summary
log_success "${BOLD}Installation complete!${NC}"
echo
log_info "Files installed:"
log_step "API Controller: $API_CONTROLLER_DIR/ProjectSyncController.php"
log_step "Enhanced Analyzer: $LOCAL_BIN/project-organizer-ai"
log_step "Sync Script: $LOCAL_BIN/sync-to-notes"
echo

log_info "${BOLD}Quick Start:${NC}"
echo
echo "  1. Add API routes (see $INTEGRATION_DIR/api-routes.php)"
echo
echo "  2. Start Laravel:"
echo "     cd $NOTES_APP_PATH"
echo "     php artisan serve"
echo
echo "  3. Verify API:"
echo "     curl http://localhost:8000/api/sync/stats"
echo
echo "  4. Run analysis:"
echo "     project-organizer ~/Code"
echo "     project-organizer-ai"
echo
echo "  5. Sync to Notes.test:"
echo "     sync-to-notes              # Dry-run preview"
echo "     sync-to-notes --execute    # Actually sync"
echo
echo "  6. View in Notes.test:"
echo "     open http://notes.test/admin/projects"
echo

log_info "Full documentation: $INTEGRATION_DIR/INSTALLATION.md"
echo
