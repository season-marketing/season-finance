#!/usr/bin/env bash
#
# Runs once, as root, AFTER this repo has already been git-cloned to
# /var/www/seasonfinance (see deploy/DEPLOY.md) - it reads
# nginx-seasonfinance.conf from that checkout. Installs nginx + PHP-FPM (NOT
# plain CGI - see usa-ping's incident writeup for why that matters) on Ubuntu
# 24.04, which ships PHP 8.3 in its own repos.
#
# Unlike usa-ping, vendor/ is already committed to this repo (Composer isn't
# re-run here) - deliberately, to avoid re-resolving dependencies against
# whatever PHP happens to run composer on deploy, which caused a real
# lock-file/PHP-version mismatch on usa-ping.
#
set -euo pipefail
exec > /var/log/bootstrap.log 2>&1

PHP_VERSION="8.3"
APP_DOMAIN="seasonfinance.com"
APP_ROOT="/var/www/seasonfinance"

export DEBIAN_FRONTEND=noninteractive
apt-get update
apt-get -y upgrade

apt-get -y install curl unzip git ufw

apt-get -y install \
  nginx \
  "php${PHP_VERSION}-fpm" "php${PHP_VERSION}-curl" "php${PHP_VERSION}-mbstring" \
  "php${PHP_VERSION}-xml" "php${PHP_VERSION}-cli" \
  certbot python3-certbot-nginx

mkdir -p "$APP_ROOT"
chown -R www-data:www-data "$APP_ROOT"

# --- PHP-FPM pool: hard backstop independent of application code ------------
# Read from the repo (deploy/php-fpm-pool.conf) rather than generated inline,
# so deploy.sh can keep it in sync on every redeploy - otherwise pool
# settings only ever take effect once, at initial bootstrap, and silently
# drift from the repo (this bit usa-ping before the pattern was fixed there).
cp "$APP_ROOT/deploy/php-fpm-pool.conf" "/etc/php/${PHP_VERSION}/fpm/pool.d/seasonfinance.conf"

# Remove the default pool listening on the same port range to avoid confusion
rm -f "/etc/php/${PHP_VERSION}/fpm/pool.d/www.conf"

systemctl enable "php${PHP_VERSION}-fpm"
systemctl restart "php${PHP_VERSION}-fpm"

# --- nginx site -------------------------------------------------------------
# Use the HTTP-only bootstrap config, not nginx-seasonfinance.conf directly -
# the real one's 443 block references a cert that doesn't exist yet on a
# brand-new instance, which would fail `nginx -t` and (under set -e) abort
# this script before nginx ever reloads. DEPLOY.md's SSL step swaps to the
# real config once certbot has actually obtained a certificate.
rm -f /etc/nginx/sites-enabled/default
cp "$APP_ROOT/deploy/nginx-seasonfinance-bootstrap.conf" "/etc/nginx/sites-available/${APP_DOMAIN}"
ln -sf "/etc/nginx/sites-available/${APP_DOMAIN}" "/etc/nginx/sites-enabled/${APP_DOMAIN}"
nginx -t && systemctl enable nginx && systemctl restart nginx

# --- firewall ----------------------------------------------------------------
ufw allow OpenSSH
ufw allow 'Nginx Full'
ufw --force enable

echo "Bootstrap complete. Run the sanity-check curl from deploy/DEPLOY.md next."
