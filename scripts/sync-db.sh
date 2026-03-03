#!/usr/bin/env bash
#
# sync-db.sh — Pull production DB to local environment
#
# Usage: bash scripts/sync-db.sh
# Run from: app/ directory (E:/LocalSites/autohakiautozpro/app/)
#
# Prerequisites:
#   - SSH access to production (key-based auth recommended)
#   - LocalWP MySQL running on port 10064
#   - WP-CLI available locally (or use LocalWP's PHP + wp-cli.phar)

set -euo pipefail

# ============================================================
# Configuration
# ============================================================

# Production server
REMOTE_USER="wiktor1249"
REMOTE_HOST="wiktor1249.ssh.dhosting.pl"
REMOTE_PHP="php82"
REMOTE_WPCLI="php82 ~/wp-cli.phar --path=public/wp"
REMOTE_APP_DIR="~/zahakowani.pl/app"

# Production site URL
PROD_URL="https://zahakowani.pl"

# Local site URL
LOCAL_URL="http://autohakiautozpro.local"

# Local DB (LocalWP)
LOCAL_DB_HOST="127.0.0.1"
LOCAL_DB_PORT="10064"
LOCAL_DB_USER="root"
LOCAL_DB_PASS="root"
LOCAL_DB_NAME="local"

# Local MySQL binary (LocalWP)
MYSQL_BIN="${MYSQL_BIN:-C:/Users/wdabe/AppData/Roaming/Local/lightning-services/mysql-8.0.16+8/bin/win64/bin/mysql.exe}"
MYSQLDUMP_BIN="${MYSQLDUMP_BIN:-C:/Users/wdabe/AppData/Roaming/Local/lightning-services/mysql-8.0.16+8/bin/win64/bin/mysqldump.exe}"

# Local WP-CLI (via LocalWP PHP + wp-cli.phar)
LOCAL_PHP="${LOCAL_PHP:-C:/Users/wdabe/AppData/Roaming/Local/lightning-services/php-8.2.27+1/bin/win64/php.exe}"
LOCAL_WPCLI="${LOCAL_WPCLI:-$LOCAL_PHP -c php-cli.ini wp-cli.phar}"

# Paths
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
APP_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"
SQL_DIR="$APP_DIR/sql"
DUMP_FILE="$SQL_DIR/zahakowani-production.sql"
REMOTE_DUMP="/tmp/zahakowani-dump.sql"

# ============================================================
# Functions
# ============================================================

log() { echo -e "\n\033[1;34m→ $1\033[0m"; }
success() { echo -e "\033[1;32m✓ $1\033[0m"; }
error() { echo -e "\033[1;31m✗ $1\033[0m" >&2; exit 1; }

# ============================================================
# Preflight checks
# ============================================================

log "Preflight checks"

# Check SSH connectivity
ssh -o ConnectTimeout=5 -o BatchMode=yes "$REMOTE_USER@$REMOTE_HOST" "echo ok" > /dev/null 2>&1 \
    || error "Cannot connect to $REMOTE_HOST. Set up SSH key auth first:\n  ssh-copy-id $REMOTE_USER@$REMOTE_HOST"

# Check local MySQL
"$MYSQL_BIN" --host="$LOCAL_DB_HOST" --port="$LOCAL_DB_PORT" --user="$LOCAL_DB_USER" --password="$LOCAL_DB_PASS" \
    -e "SELECT 1" > /dev/null 2>&1 \
    || error "Cannot connect to local MySQL on port $LOCAL_DB_PORT. Is LocalWP running?"

mkdir -p "$SQL_DIR"
success "All checks passed"

# ============================================================
# Step 1: Export production DB
# ============================================================

log "Exporting production database via SSH"

ssh "$REMOTE_USER@$REMOTE_HOST" "cd $REMOTE_APP_DIR && $REMOTE_WPCLI db export $REMOTE_DUMP --quiet"
success "Production DB exported to $REMOTE_DUMP"

# ============================================================
# Step 2: Download dump
# ============================================================

log "Downloading dump (this may take a minute for large DBs)"

scp "$REMOTE_USER@$REMOTE_HOST:$REMOTE_DUMP" "$DUMP_FILE"

# Clean up remote dump
ssh "$REMOTE_USER@$REMOTE_HOST" "rm -f $REMOTE_DUMP"

DUMP_SIZE=$(du -h "$DUMP_FILE" | cut -f1)
success "Downloaded $DUMP_FILE ($DUMP_SIZE)"

# ============================================================
# Step 3: Backup local DB
# ============================================================

log "Backing up local database"

BACKUP_FILE="$SQL_DIR/local-backup-$(date +%Y%m%d-%H%M%S).sql"
"$MYSQLDUMP_BIN" --host="$LOCAL_DB_HOST" --port="$LOCAL_DB_PORT" \
    --user="$LOCAL_DB_USER" --password="$LOCAL_DB_PASS" \
    "$LOCAL_DB_NAME" > "$BACKUP_FILE" 2>/dev/null

success "Local backup saved to $BACKUP_FILE"

# ============================================================
# Step 4: Import production dump
# ============================================================

log "Importing production dump into local DB"

# Drop and recreate to start clean
"$MYSQL_BIN" --host="$LOCAL_DB_HOST" --port="$LOCAL_DB_PORT" \
    --user="$LOCAL_DB_USER" --password="$LOCAL_DB_PASS" \
    -e "DROP DATABASE IF EXISTS \`$LOCAL_DB_NAME\`; CREATE DATABASE \`$LOCAL_DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import
"$MYSQL_BIN" --host="$LOCAL_DB_HOST" --port="$LOCAL_DB_PORT" \
    --user="$LOCAL_DB_USER" --password="$LOCAL_DB_PASS" \
    "$LOCAL_DB_NAME" < "$DUMP_FILE"

success "Production dump imported"

# ============================================================
# Step 5: Search-replace URLs
# ============================================================

log "Running WP-CLI search-replace (handles serialized data)"

cd "$APP_DIR"

# HTTPS production → local
$LOCAL_WPCLI search-replace "$PROD_URL" "$LOCAL_URL" --all-tables --precise --recurse-objects --skip-columns=guid

# HTTP production → local (in case of mixed content)
$LOCAL_WPCLI search-replace "http://zahakowani.pl" "$LOCAL_URL" --all-tables --precise --recurse-objects --skip-columns=guid

success "URLs replaced"

# ============================================================
# Step 6: Flush caches
# ============================================================

log "Flushing caches and rewrite rules"

$LOCAL_WPCLI cache flush 2>/dev/null || true
$LOCAL_WPCLI transient delete --all 2>/dev/null || true
$LOCAL_WPCLI rewrite flush 2>/dev/null || true

success "Caches flushed"

# ============================================================
# Step 7: Verify
# ============================================================

log "Verifying site URLs"

SITEURL=$($LOCAL_WPCLI option get siteurl)
HOME_URL=$($LOCAL_WPCLI option get home)

echo "  siteurl: $SITEURL"
echo "  home:    $HOME_URL"

if [[ "$HOME_URL" == "$LOCAL_URL" ]]; then
    success "Sync complete! Visit $LOCAL_URL"
else
    error "home URL is '$HOME_URL' — expected '$LOCAL_URL'. Something went wrong."
fi

echo ""
echo "Tip: If you need to restore the local DB:"
echo "  $MYSQL_BIN --host=$LOCAL_DB_HOST --port=$LOCAL_DB_PORT --user=$LOCAL_DB_USER --password=$LOCAL_DB_PASS $LOCAL_DB_NAME < $BACKUP_FILE"
