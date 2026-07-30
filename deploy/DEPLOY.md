# Deploying seasonfinance.com to EC2

Moves the full site (frontend + API) off the current shared host onto a
dedicated EC2 instance running nginx + PHP-FPM (not CGI - see `usa-ping`'s
incident writeup for why that matters: CGI's fork-per-request model is what
caused a multi-day worker-exhaustion outage there).

Broader in scope than `usa-ping`'s deploy: this is a real website (Blade-
templated homepage, webpack/Tailwind-built frontend assets) plus the same
kind of lead-posting API backend, not just the API alone.

## Known gaps

- **Fixed since this doc was first written**: `api.php`'s zero-timeout curl
  bug is resolved (`CURLOPT_TIMEOUT` 120s, `CURLOPT_CONNECTTIMEOUT` 30s), and
  `leads.php` sets `max_execution_time` to 300s instead of unlimited. The
  PHP-FPM `request_terminate_timeout` / nginx `fastcgi_read_timeout` (both
  315s, see `deploy/php-fpm-pool.conf`) are set to stay above both of those,
  same reasoning as `usa-ping`'s equivalent - if you change either app-level
  timeout, these two need to move together with it or the same premature-
  kill bug comes back.
- **`leads.php` still points at the bare IP `http://35.177.229.97`** instead
  of the `portal.seasonmarketing.co.uk` hostname, for the live (non-dev)
  path. Worth knowing why this matters, from the parallel investigation on
  `usa-ping`: `portal.seasonmarketing.co.uk` sits behind Cloudflare, which
  has its own ~100s edge timeout that fails fast with a `524` if the origin
  is slow. Calling the bare IP skips Cloudflare entirely, so a slow response
  can now hang for the *full* 120s curl timeout (or up to the 315s FPM
  ceiling for other slowness) instead of failing fast - not obviously worse,
  but a different failure mode than `usa-ping` currently has. Not changed
  here since it wasn't part of this deploy task.
- **Fixed**: `Contact.php` used to send email via PHP's `mail()`, which
  needs a local MTA this instance doesn't have, and AWS blocks outbound port
  25 from EC2 by default - it would have silently failed to send. Now routes
  through `portal`'s `actionSendEmail` API instead (see `portal`'s
  `send-email-api` branch, `modules/api/controllers/ApiController.php`) -
  same secret-auth as every other endpoint here, no local mail transport
  needed. Requires that branch to be merged/deployed on `portal`'s side
  before this actually works end to end.

## Prerequisites

- Access to wherever DNS for `seasonfinance.com` is managed.
- Push/pull access to `git@github.com:season-marketing/season-finance.git`.

## 1. Create the instance (AWS Console)

1. Switch the AWS Console region (top right) to **eu-west-2 (London)** -
   same region as `usa-ping` and the upstream API server, for consistency
   and to keep the same private-IP optimization available later (see
   "Networking" below).
2. EC2 -> Launch instance.
3. AMI: **Ubuntu Server 24.04 LTS** (plain, not "with SQL Server"), arch
   **64-bit (x86)**.
4. Instance type: **t3.small** to start.
5. Key pair: create a new one (e.g. `seasonfinance-key`), download the
   `.pem`, `chmod 400` it locally.
6. Network settings:
   - Auto-assign public IP: **Enable**
   - Security group: SSH (22) from **My IP** only, HTTP (80) and HTTPS (443)
     from Anywhere
   - VPC/subnet: pick `vpc-9f641af7` / `subnet-a3820ed9` (same as `usa-ping`
     and the API server) if you want the private-IP option later: see
     "Networking" section below. Not required to get the site running.
7. Storage: 20 GB gp3.
8. Launch, then allocate and associate an **Elastic IP**.

## 2. Deploy the code

SSH in, install git:

```bash
ssh -i seasonfinance-key.pem ubuntu@<ELASTIC_IP>
sudo apt-get update && sudo apt-get install -y git
```

Private repo - generate a deploy key **as root** (every git command here
runs via `sudo`, so the key needs to live in root's home):

```bash
sudo ssh-keygen -t ed25519 -C "seasonfinance-ec2" -f /root/.ssh/id_ed25519 -N ""
sudo cat /root/.ssh/id_ed25519.pub
```

Copy that output into GitHub: repo `season-marketing/season-finance` ->
Settings -> Deploy keys -> Add deploy key -> paste -> leave "Allow write
access" **unchecked** -> Save.

Clone and fix the ownership check:

```bash
sudo ssh -o StrictHostKeyChecking=accept-new -T git@github.com
sudo git clone git@github.com:season-marketing/season-finance.git /var/www/seasonfinance
sudo git config --global --add safe.directory /var/www/seasonfinance
```

## 3. Bootstrap the server

```bash
sudo bash /var/www/seasonfinance/deploy/bootstrap.sh
tail -f /var/log/bootstrap.log
```

Wait for `Bootstrap complete.` Sanity check before DNS points here yet:

```bash
curl -H "Host: seasonfinance.com" http://<ELASTIC_IP>/
```

Should return the homepage HTML (look for `<title>Season Finance`), not an
nginx error page.

## 4. Redeploying later

For changes to `nginx-seasonfinance.conf`, `php-fpm-pool.conf`, or
`bootstrap.sh`, use `deploy.sh` - it backs up configs first, tests both
nginx and PHP-FPM configs before reloading, and auto-rolls-back if either
test fails:

```bash
sudo bash /var/www/seasonfinance/deploy/deploy.sh
```

For plain PHP/Blade/asset changes where nothing under `deploy/` changed,
`quick-pull.sh` is faster - just `git pull` + fix ownership, no config
sync/test/reload:

```bash
sudo bash /var/www/seasonfinance/deploy/quick-pull.sh
```

Roll back the last `deploy.sh` run with `sudo bash /var/www/seasonfinance/deploy/rollback.sh`.
See `usa-ping`'s `DEPLOY.md` for the fuller explanation of how these work -
identical mechanism here, just no `composer install` step (see the top of
`deploy.sh` for why).

## 5. DNS cutover

Point `seasonfinance.com`'s (and `www.seasonfinance.com`'s) A record at the
Elastic IP. Lower the TTL first and let it propagate before changing the
value, same reasoning as `usa-ping`: makes the cutover reversible in minutes
instead of however long the old TTL was. Confirm with
`dig +short seasonfinance.com`.

## 6. SSL

```bash
sudo certbot certonly --nginx -d seasonfinance.com -d www.seasonfinance.com \
  --non-interactive --agree-tos -m <email>
```

Note **`certonly`** - do NOT use plain `certbot --nginx` (no `certonly`) -
that lets certbot edit the live config file directly, and the next
`deploy.sh`/`cp` would silently overwrite those edits and take HTTPS down,
exactly like it did on `usa-ping` the first time.

Now swap from the bootstrap HTTP-only config to the real one, which has the
443 block (the cert now exists, so this will pass):

```bash
sudo cp /var/www/seasonfinance/deploy/nginx-seasonfinance.conf /etc/nginx/sites-available/seasonfinance.com
sudo nginx -t && sudo systemctl reload nginx
```

## 7. Verify end to end

```bash
curl https://seasonfinance.com/
curl https://seasonfinance.com/api/lead -d '{}'
```

Also click through the actual site in a browser - images, CSS, and JS from
`dist/`/`app/Resources/` should load; check the browser console for 404s on
static assets, which would mean a path assumption in
`nginx-seasonfinance.conf` doesn't match reality.

## Networking: private connection to the upstream API (optional, revisit anytime)

Same idea as `usa-ping`'s equivalent section - if this instance is in
`vpc-9f641af7`/`subnet-a3820ed9` (step 1), you can route
`portal.seasonmarketing.co.uk` over its private IP (`172.31.21.11`) instead
of the public internet. Needs the API server's security group to allow this
instance first (see `usa-ping`'s `DEPLOY.md` for the exact steps - identical
here), then:

```bash
echo "172.31.21.11 portal.seasonmarketing.co.uk" | sudo tee -a /etc/hosts
```

Don't do this before the security group change is confirmed working -
same caveat as `usa-ping`: it breaks every outbound API call instead of
being a no-op until that's in place.
