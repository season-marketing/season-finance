#!/usr/bin/env bash
#
# Run ON THE SERVER as root: sudo bash /var/www/seasonfinance/deploy/quick-pull.sh
#
# Just `git pull` + fix ownership - nothing else. Use this for plain PHP/
# Blade/asset changes where nginx-seasonfinance.conf and php-fpm-pool.conf
# are untouched. PHP-FPM re-reads .php files fresh on every request, so no
# reload is needed for code-only changes to take effect.
#
# If your change DOES touch anything under deploy/ (nginx config, FPM pool
# config, bootstrap.sh), use deploy.sh instead - this script deliberately
# does not test or reload either, so a config change made this way would
# just sit there un-applied rather than break anything, but it also won't
# take effect until someone runs deploy.sh anyway.
#
set -euo pipefail

if [ "$(id -u)" -ne 0 ]; then
    echo "Run this with sudo." >&2
    exit 1
fi

APP_ROOT="/var/www/seasonfinance"

echo "==> Pulling latest code"
git -C "$APP_ROOT" pull

echo "==> Fixing ownership (git pull runs as root, so new/changed files come out root-owned)"
chown -R www-data:www-data "$APP_ROOT"

echo "==> Done. No reload needed for PHP-only changes."
