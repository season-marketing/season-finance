#!/usr/bin/env bash
#
# Run ON THE SERVER as root: sudo bash /var/www/seasonfinance/deploy/deploy.sh
#
# Backs up the live nginx + PHP-FPM configs before touching anything, pulls
# the latest code, applies the new nginx config, and only reloads if
# `nginx -t` passes - rolling back automatically if it fails, so a broken
# config never actually goes live.
#
# No `composer install` here - vendor/ is committed directly to this repo
# (unlike usa-ping), specifically to avoid re-resolving dependencies against
# whatever PHP happens to be running composer at deploy time.
#
set -euo pipefail

if [ "$(id -u)" -ne 0 ]; then
    echo "Run this with sudo." >&2
    exit 1
fi

APP_ROOT="/var/www/seasonfinance"
NGINX_CONF_LIVE="/etc/nginx/sites-available/seasonfinance.com"
FPM_POOL_LIVE="/etc/php/8.3/fpm/pool.d/seasonfinance.conf"
BACKUP_ROOT="/var/backups/seasonfinance"
KEEP_BACKUPS=10

TIMESTAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP_DIR="$BACKUP_ROOT/$TIMESTAMP"

echo "==> Backing up current state to $BACKUP_DIR"
mkdir -p "$BACKUP_DIR"
cp "$NGINX_CONF_LIVE" "$BACKUP_DIR/nginx-seasonfinance.conf" 2>/dev/null \
    && echo "    nginx config backed up" \
    || echo "    (no existing nginx config - first deploy?)"
cp "$FPM_POOL_LIVE" "$BACKUP_DIR/php-fpm-seasonfinance.conf" 2>/dev/null \
    && echo "    php-fpm pool config backed up" \
    || echo "    (no existing FPM pool config)"
git -C "$APP_ROOT" rev-parse HEAD > "$BACKUP_DIR/git-commit.txt" 2>/dev/null || true
echo "$BACKUP_DIR" > "$BACKUP_ROOT/latest"

echo "==> Pruning old backups (keeping last $KEEP_BACKUPS)"
ls -1dt "$BACKUP_ROOT"/*/ 2>/dev/null | tail -n +$((KEEP_BACKUPS + 1)) | xargs -r rm -rf

echo "==> Pulling latest code"
git -C "$APP_ROOT" pull

echo "==> Fixing ownership (git pull runs as root, so new/changed files come out root-owned)"
chown -R www-data:www-data "$APP_ROOT"

echo "==> Applying nginx config"
cp "$APP_ROOT/deploy/nginx-seasonfinance.conf" "$NGINX_CONF_LIVE"

echo "==> Testing nginx config"
if ! nginx -t; then
    echo "!! nginx config test FAILED - rolling back automatically"
    if [ -f "$BACKUP_DIR/nginx-seasonfinance.conf" ]; then
        cp "$BACKUP_DIR/nginx-seasonfinance.conf" "$NGINX_CONF_LIVE"
        nginx -t && systemctl reload nginx
        echo "!! Rolled back nginx config to pre-deploy state."
    else
        echo "!! No prior nginx config to roll back to - manual fix needed."
    fi
    echo "!! Deploy aborted. Fix deploy/nginx-seasonfinance.conf and retry - code was still pulled, only the config was rolled back."
    exit 1
fi

echo "==> Reloading nginx and PHP-FPM"
systemctl reload nginx
systemctl reload php8.3-fpm

echo "==> Health check"
STATUS="$(curl -s -o /dev/null -w '%{http_code}' https://seasonfinance.com/ || echo 000)"
if [[ "$STATUS" != 2* ]]; then
    echo "!! Health check got HTTP $STATUS for / (expected 2xx)."
    echo "!! Run 'sudo bash deploy/rollback.sh' to revert to the pre-deploy state at $BACKUP_DIR."
    exit 1
fi

echo "==> Deploy complete. Pre-deploy backup saved at $BACKUP_DIR (roll back with: sudo bash deploy/rollback.sh)"
