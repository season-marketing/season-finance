#!/usr/bin/env bash
#
# Run ON THE SERVER as root: sudo bash /var/www/seasonfinance/deploy/rollback.sh
#
# Restores nginx + PHP-FPM configs (and optionally the code) from a backup
# taken by deploy.sh. With no argument, rolls back to the most recent backup
# - i.e. undoes whatever the last deploy.sh run just did.
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

BACKUP_DIR="${1:-}"
if [ -z "$BACKUP_DIR" ]; then
    if [ ! -f "$BACKUP_ROOT/latest" ]; then
        echo "No backups found (no $BACKUP_ROOT/latest) and none specified." >&2
        exit 1
    fi
    BACKUP_DIR="$(cat "$BACKUP_ROOT/latest")"
fi

if [ ! -d "$BACKUP_DIR" ]; then
    echo "Backup directory not found: $BACKUP_DIR" >&2
    echo "Available backups:" >&2
    ls -1dt "$BACKUP_ROOT"/*/ 2>/dev/null >&2
    exit 1
fi

echo "==> Rolling back to: $BACKUP_DIR"

if [ -f "$BACKUP_DIR/nginx-seasonfinance.conf" ]; then
    cp "$BACKUP_DIR/nginx-seasonfinance.conf" "$NGINX_CONF_LIVE"
    echo "    nginx config restored"
fi
if [ -f "$BACKUP_DIR/php-fpm-seasonfinance.conf" ]; then
    cp "$BACKUP_DIR/php-fpm-seasonfinance.conf" "$FPM_POOL_LIVE"
    echo "    php-fpm pool config restored"
fi

if [ -f "$BACKUP_DIR/git-commit.txt" ]; then
    COMMIT="$(cat "$BACKUP_DIR/git-commit.txt")"
    echo "    reverting code to commit $COMMIT"
    git -C "$APP_ROOT" checkout "$COMMIT" -- .
fi

echo "==> Testing nginx and PHP-FPM configs"
nginx -t
php-fpm8.3 -t

echo "==> Reloading nginx and PHP-FPM"
systemctl reload nginx
systemctl reload php8.3-fpm

echo "==> Rollback complete."
