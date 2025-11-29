#!/usr/bin/env bash
set -euo pipefail

# === CONFIG ===
MAIN_DIR="/Users/davepeloso/Herd/notes"

LOCAL_BIN="/Users/davepeloso/.local/bin"
INSTALL_NOTES="/Users/Shared/Projects/install-notes-brain"
PROJECT_DATA="/Users/davepeloso/.project_data"

# Project-specific files currently living in ~/.local/bin
PROJECT_BIN_DIR_NAME="local-bin"
PROJECT_BIN_DIR="$MAIN_DIR/$PROJECT_BIN_DIR_NAME"

PROJECT_BIN_FILES=(
  "project-organizer"
  "project-organizer-ai"
  "project-organizer-ai.code-workspace"
  "project-organizer-ai.py"
  "project-organizer.sh"
  "project-prescan"
  "project-prescan.sh"
  "README.md"
  "sync-to-notes"
  "sync-to-notes.py"
)

# CLI entry points we want to exist in ~/.local/bin after the move
# and where they should point inside the repo
declare -A PROJECT_BIN_ENTRYPOINTS=(
  ["project-organizer"]="$PROJECT_BIN_DIR_NAME/project-organizer.sh"
  ["project-organizer-ai"]="$PROJECT_BIN_DIR_NAME/project-organizer-ai.py"
  ["project-prescan"]="$PROJECT_BIN_DIR_NAME/project-prescan.sh"
  ["sync-to-notes"]="$PROJECT_BIN_DIR_NAME/sync-to-notes.py"
)

echo "== Verifying core directories exist =="
for d in "$MAIN_DIR" "$LOCAL_BIN" "$INSTALL_NOTES" "$PROJECT_DATA"; do
  if [ ! -d "$d" ]; then
    echo "ERROR: Directory does not exist: $d" >&2
    exit 1
  else
    echo "OK: $d"
  fi
done

echo
echo "== Verifying current symlink structure in $MAIN_DIR =="
# We expect these to currently be symlinks in notes/
for link in "local-bin" "install-notes-brain" "project_data"; do
  path="$MAIN_DIR/$link"
  if [ -L "$path" ]; then
    echo "OK: $path is a symlink (as expected)"
  elif [ -e "$path" ]; then
    echo "ERROR: $path exists but is not a symlink. Aborting to avoid clobbering." >&2
    exit 1
  else
    echo "WARNING: $path does not exist. Continuing, but structure may differ from expected."
  fi
done

echo
echo "== Removing symlinks in $MAIN_DIR so we can create real dirs =="
for link in "local-bin" "install-notes-brain" "project_data"; do
  path="$MAIN_DIR/$link"
  if [ -L "$path" ]; then
    echo "Removing symlink: $path"
    rm "$path"
  fi
done

echo
echo "== Moving install-notes-brain and project_data INTO repo =="

# Move install-notes-brain
if [ -d "$INSTALL_NOTES" ]; then
  if [ -e "$MAIN_DIR/install-notes-brain" ]; then
    echo "ERROR: Destination already exists: $MAIN_DIR/install-notes-brain" >&2
    exit 1
  fi
  echo "Moving $INSTALL_NOTES -> $MAIN_DIR/install-notes-brain"
  mv "$INSTALL_NOTES" "$MAIN_DIR/install-notes-brain"
else
  echo "WARNING: $INSTALL_NOTES not found, skipping move."
fi

# Move project_data
if [ -d "$PROJECT_DATA" ]; then
  if [ -e "$MAIN_DIR/project_data" ]; then
    echo "ERROR: Destination already exists: $MAIN_DIR/project_data" >&2
    exit 1
  fi
  echo "Moving $PROJECT_DATA -> $MAIN_DIR/project_data"
  mv "$PROJECT_DATA" "$MAIN_DIR/project_data"
else
  echo "WARNING: $PROJECT_DATA not found, skipping move."
fi

echo
echo "== Creating backlink symlinks at original locations =="

# Recreate symlink: original locations -> repo locations
if [ ! -e "$INSTALL_NOTES" ] && [ ! -L "$INSTALL_NOTES" ]; then
  echo "Creating symlink: $INSTALL_NOTES -> $MAIN_DIR/install-notes-brain"
  ln -s "$MAIN_DIR/install-notes-brain" "$INSTALL_NOTES"
else
  echo "WARNING: $INSTALL_NOTES already exists; not creating backlink."
fi

if [ ! -e "$PROJECT_DATA" ] && [ ! -L "$PROJECT_DATA" ]; then
  echo "Creating symlink: $PROJECT_DATA -> $MAIN_DIR/project_data"
  ln -s "$MAIN_DIR/project_data" "$PROJECT_DATA"
else
  echo "WARNING: $PROJECT_DATA already exists; not creating backlink."
fi

echo
echo "== Creating project bin directory inside repo: $PROJECT_BIN_DIR =="
if [ -e "$PROJECT_BIN_DIR" ] && [ ! -d "$PROJECT_BIN_DIR" ]; then
  echo "ERROR: $PROJECT_BIN_DIR exists but is not a directory." >&2
  exit 1
fi
mkdir -p "$PROJECT_BIN_DIR"

echo
echo "== Moving ONLY project-related files from $LOCAL_BIN into $PROJECT_BIN_DIR =="
for fname in "${PROJECT_BIN_FILES[@]}"; do
  src="$LOCAL_BIN/$fname"
  if [ -e "$src" ] || [ -L "$src" ]; then
    echo "Moving $src -> $PROJECT_BIN_DIR/"
    mv "$src" "$PROJECT_BIN_DIR/"
  else
    echo "WARNING: $src not found; skipping."
  fi
done

echo
echo "== Recreating CLI entrypoint symlinks in $LOCAL_BIN pointing into repo =="

for name in "${!PROJECT_BIN_ENTRYPOINTS[@]}"; do
  target_rel="${PROJECT_BIN_ENTRYPOINTS[$name]}"
  link_path="$LOCAL_BIN/$name"
  target_path="$MAIN_DIR/$target_rel"

  if [ -e "$link_path" ] || [ -L "$link_path" ]; then
    echo "WARNING: $link_path already exists; skipping creation of entrypoint symlink."
    continue
  fi

  if [ ! -e "$target_path" ] && [ ! -L "$target_path" ]; then
    echo "WARNING: Target for $name does not exist: $target_path (skipping this entrypoint)"
    continue
  fi

  echo "Creating symlink: $link_path -> $target_path"
  ln -s "$target_path" "$link_path"
done

echo
echo "== Ensuring .gitignore is updated =="

GITIGNORE_PATH="$MAIN_DIR/.gitignore"
touch "$GITIGNORE_PATH"

add_gitignore_entry() {
  local entry="$1"
  if ! grep -qxF "$entry" "$GITIGNORE_PATH"; then
    echo "$entry" >> "$GITIGNORE_PATH"
    echo "Added to .gitignore: $entry"
  else
    echo ".gitignore already contains: $entry"
  fi
}

add_gitignore_entry ".DS_Store"
add_gitignore_entry ".env"
add_gitignore_entry "node_modules"
add_gitignore_entry "vendor"
add_gitignore_entry "storage/logs"
add_gitignore_entry "storage/framework/cache"

echo
echo "== Finalizing Git commit (optional) =="

cd "$MAIN_DIR"
git status

# Comment this block out if you want to review changes manually first
git add .
git commit -m "Restructure: move install-notes-brain, project_data, and project bin files into repo"

echo
echo "✅ Migration complete."
echo "Repo root: $MAIN_DIR"
echo "Now inside repo:"
echo "  $MAIN_DIR/install-notes-brain"
echo "  $MAIN_DIR/project_data"
echo "  $PROJECT_BIN_DIR (with project scripts)"
echo "External paths now symlink into repo:"
echo "  $INSTALL_NOTES -> $MAIN_DIR/install-notes-brain"
echo "  $PROJECT_DATA  -> $MAIN_DIR/project_data"
echo "  selected ~/.local/bin entrypoints -> $PROJECT_BIN_DIR"
