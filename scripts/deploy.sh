#!/usr/bin/env bash
#
# deploy.sh — Manual rsync deploy to production
#
# Usage: bash scripts/deploy.sh [--dry-run]
# Run from: app/ directory (E:/LocalSites/autohakiautozpro/app/)
#
# This is a backup for when GitHub Actions is unavailable.
# Prefer pushing to main branch for automatic deploys.

set -euo pipefail

# ============================================================
# Configuration
# ============================================================

REMOTE_USER="wiktor1249"
REMOTE_HOST="wiktor1249.ssh.dhosting.pl"
REMOTE_APP_DIR="~/zahakowani.pl/app"
REMOTE_PHP="php82"
REMOTE_COMPOSER="php82 ~/composer.phar"
REMOTE_WPCLI="php82 ~/wp-cli.phar --path=public/wp"

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
APP_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"

DRY_RUN=""
if [[ "${1:-}" == "--dry-run" ]]; then
    DRY_RUN="--dry-run"
    echo "=== DRY RUN MODE — no files will be transferred ==="
fi

# ============================================================
# Functions
# ============================================================

log() { echo -e "\n\033[1;34m→ $1\033[0m"; }
success() { echo -e "\033[1;32m✓ $1\033[0m"; }
error() { echo -e "\033[1;31m✗ $1\033[0m" >&2; exit 1; }

# ============================================================
# Preflight
# ============================================================

log "Preflight checks"

# Check SSH
ssh -o ConnectTimeout=5 -o BatchMode=yes "$REMOTE_USER@$REMOTE_HOST" "echo ok" > /dev/null 2>&1 \
    || error "Cannot connect to $REMOTE_HOST"

# Check rsync
command -v rsync > /dev/null 2>&1 \
    || error "rsync not found. Install with: pacman -S rsync (MSYS2/Git Bash)"

# Check we're on main branch
CURRENT_BRANCH=$(git -C "$APP_DIR" rev-parse --abbrev-ref HEAD 2>/dev/null || echo "unknown")
if [[ "$CURRENT_BRANCH" != "main" && -z "$DRY_RUN" ]]; then
    echo "WARNING: You're on branch '$CURRENT_BRANCH', not 'main'."
    read -p "Continue anyway? [y/N] " -r
    [[ $REPLY =~ ^[Yy]$ ]] || exit 1
fi

# Check for uncommitted changes
if git -C "$APP_DIR" diff --quiet 2>/dev/null && git -C "$APP_DIR" diff --cached --quiet 2>/dev/null; then
    success "Working tree is clean"
else
    echo "WARNING: You have uncommitted changes."
    read -p "Deploy anyway? [y/N] " -r
    [[ $REPLY =~ ^[Yy]$ ]] || exit 1
fi

success "Preflight passed"

# ============================================================
# Deploy via rsync
# ============================================================

log "Syncing files to production"

rsync -avz $DRY_RUN \
    --exclude='.env' \
    --exclude='auth.json' \
    --exclude='.git/' \
    --exclude='.github/' \
    --exclude='vendor/' \
    --exclude='node_modules/' \
    --exclude='public/wp/' \
    --exclude='public/wp-content/plugins/' \
    --exclude='public/wp-content/mu-plugins/' \
    --exclude='public/wp-content/uploads/' \
    --exclude='public/wp-content/cache/' \
    --exclude='public/wp-content/debug.log' \
    --exclude='sql/' \
    --exclude='php-cli.ini' \
    --exclude='composer.phar' \
    --exclude='wp-cli.phar' \
    --exclude='*.jsonl' \
    --exclude='.DS_Store' \
    --exclude='Thumbs.db' \
    --exclude='premium/themes/autozpro/inc/merlin/vendor/' \
    --exclude='premium/plugins/inpost-pay/vendor/' \
    "$APP_DIR/" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_APP_DIR/"

success "Files synced"

if [[ -n "$DRY_RUN" ]]; then
    echo ""
    echo "Dry run complete. Run without --dry-run to deploy for real."
    exit 0
fi

# ============================================================
# Post-deploy: composer install + cache flush
# ============================================================

log "Running composer install on server"

ssh "$REMOTE_USER@$REMOTE_HOST" "cd $REMOTE_APP_DIR && $REMOTE_COMPOSER install --no-dev --optimize-autoloader --no-interaction 2>&1"
success "Composer install done"

log "Flushing cache"

ssh "$REMOTE_USER@$REMOTE_HOST" "cd $REMOTE_APP_DIR && $REMOTE_WPCLI cache flush 2>/dev/null || true"
success "Cache flushed"

# ============================================================
# Verify
# ============================================================

log "Verifying production site"

HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" "https://zahakowani.pl/" 2>/dev/null || echo "000")

if [[ "$HTTP_STATUS" == "200" ]]; then
    success "Deploy complete! https://zahakowani.pl/ responds with HTTP $HTTP_STATUS"
else
    echo "WARNING: https://zahakowani.pl/ returned HTTP $HTTP_STATUS"
    echo "Check the site manually."
fi
