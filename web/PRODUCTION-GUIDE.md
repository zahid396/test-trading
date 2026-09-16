# Production Guide — Run & Manage Your Digital Product Store (A to Z)

> Written for someone who knows **nothing about code, servers, or networks**.
> Read Part 1 first, then follow the parts in order. If something goes wrong, jump to **Part 12 – Troubleshooting**.
> Your app is a finished **Laravel** project (PHP + MySQL). The website lives in the **`public`** folder; the rest are supporting files that must never be accessed from the internet.

---

## Part 1. How Your Store Works (30-second version)

- When a customer visits, PHP runs your app, reads product/order data from the **MySQL database**, and sends normal web pages to the browser.
- **You (admin)** log in at `/admin/login`, see orders, verify payments, and manually email the product to the customer.
- Every setting and piece of content (products, banners, prices, payment numbers, social links, legal text) is stored in the database and edited through the admin panel — **no coding needed to run the store**.
- The only files you might ever touch are the **`.env`** settings file and a few `php artisan ...` commands that you copy-paste.

Three things make your site work on the internet:

| Thing | What it is | Where it lives |
| ----- | ---------- | -------------- |
| **The code** (your app) | All the files in this project folder | Your hosting account |
| **The database** | MySQL, stores products/orders/settings | Your hosting account |
| **The domain** | Your website address + SSL lock | Bought from a registrar, pointed to the host |

---

## Part 2. Before You Start — Checklist

- [ ] A domain name (e.g. `yourstore.com`) — buy from any registrar (Namecheap, GoDaddy, Hostinger, Porkbun)
- [ ] Hosting that supports **PHP 8.3+** and **MySQL** (most "Laravel / PHP" plans do)
- [ ] Access to a **terminal / SSH** (not strictly required on good cPanel hosts, but makes life much easier)
- [ ] A backup of this project folder on your computer (copy the whole folder somewhere safe, zip it)

> Tip: If this is your first time, **practice on the same machine first**: install a local PHP + MySQL tool (e.g. XAMPP), then run the app with the instructions in Part 5. When it works locally, launch it online.

---

## Part 3. Choose Hosting

### Option A — Shared hosting with cPanel (easiest for beginners)
Providers: Hostinger, Namecheap, A2 Hosting, SiteGround, HostGator.
- Best if: you want a control panel with phpMyAdmin, file manager, and one-click SSL.
- Make sure the plan: PHP 8.3+, MySQL, SSH/terminal (nice to have).

### Option B — VPS (more power, needs more care)
Providers: DigitalOcean, Vultr, Linode, Hetzner.
- Best if: you expect heavy traffic or want full control.
- Requires you to run Linux commands (a one-time setup with a tutorial like the one in Part 6–9 is enough).

### What I recommend
Start with **shared hosting + cPanel**. It is cheaper and has clickable tools. The instructions below cover cPanel first and VPS second where they differ.

---

## Part 4. Domain, Email, and SSL

1. **Buy the domain.** Keep your registrar login safe.
2. **Add an email address** if you want `you@yourstore.com` (on the hosting plan or Google Workspace/Zoho).
3. **Point the domain to your hosting.** Every host explains this as "point your nameservers" — you replace the registrar's nameservers with the host's nameservers (e.g. Hostinger: `ns1.dns-parking.com`). It can take a few hours to "propagate".
   - Test: after DNS updates, opening `http://yourstore.com` should reach the hosting "parking" page.
4. **SSL certificate** — most hosts install a free one (Let's Encrypt) from the cPanel "SSL/TLS" or "AutoSSL" tool. Always have SSL on; Google marks non-SSL sites as unsafe and payments are mobile payments, still — trust matters.

---

## Part 5. Get Your Code Onto the Server

### Where the files must go
- Everything goes outside the web-accessible area **except** the contents of the `public` folder, which become your website root. On cPanel this is usually:
  - `public_html` (the visible website root) → should contain the **contents of `public/`**
  - A private folder like `storeapp` (sibling of `public_html`) → everything else from this project
- On a VPS it's typically `/var/www/yourstore` with the web server pointed at `/var/www/yourstore/public`.

### Uploading with cPanel (drag and drop)
1. Zip the whole project folder on your computer (call it `app.zip`).
2. In **cPanel File Manager**, open your home directory, create a folder `storeapp`.
3. Upload `app.zip` into `storeapp`, then "Extract" it there.
4. Create `public_html` if it doesn't exist; inside it, delete any default `index.html`.
5. Copy everything **from** `storeapp/public` **into** `public_html` (so `index.php`, `.htaccess`, `build/` are directly in `public_html`).
   - Result: `public_html/index.php` and `storeapp/...` (all the other files).
   - Do **not** put `storeapp` inside `public_html`.

> cPanel detail: the app files outside `public_html` cannot be reached from the internet — that is exactly what we want for security (the `.env` holds your passwords).

### Uploading with cPanel — SIMPLER OPTION (everything inside `public_html`)
> This project ships with a **root `index.php`** and a **root `.htaccess`**, so you can skip the `public_html` copying step above. Just put the **whole project** into `public_html`:
1. Zip the whole project folder on your computer (call it `app.zip`).
2. In **cPanel File Manager**, open `public_html` and delete any default `index.html`/`index.php`.
3. Upload `app.zip` inside `public_html` and **Extract** it there — the files (`index.php`, `.htaccess`, `app/`, `config/`, `public/`, `.env`, ...) sit directly in `public_html`.
4. The built-in root `.htaccess` automatically:
   - Maps `public/` assets (`/build`, `/storage`, favicon, robots.txt) so images and styling work.
   - **Blocks `.env`, `.git`, and every sensitive folder** (`app/`, `config/`, `database/`, `vendor/`, ...) from being downloaded.
   - Forces `http → https` and routes all requests to Laravel.
5. If your host already uses the **Option A** layout (app outside `public_html`, `public/` contents inside), the project works that way too — both setups are supported.

> Keep `public/index.php` inside `public/` either way; nothing needs to be deleted.

### Uploading with a VPS
```bash
cd /var/www
# you uploaded app.zip earlier — or copy the folder via SFTP/SCP
unzip app.zip -d /tmp/app && mv /tmp/app/* /var/www/yourstore
```

---

## Part 6. Create the MySQL Database

### cPanel
1. Open **MySQL Databases**.
2. Create a database, e.g. `yours_db`.
3. Create a **MySQL user**, e.g. `yours_user`, with a **long strong password** (write it down).
4. **Add user to database** and grant **ALL PRIVILEGES**.
5. You now have: database name, username, password, host (usually `localhost`).

### VPS
```bash
mysql -u root -p
CREATE DATABASE yourstore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'yourstore'@'localhost' IDENTIFIED BY 'USE-A-STRONG-PASSWORD';
GRANT ALL PRIVILEGES ON yourstore.* TO 'yourstore'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## Part 7. Configure the `.env` File (the most important settings)

Your project has a file named **`.env`**. It is the app's control panel in text form. Edit it in place (never rename/delete it, never back it up to the public folder, never paste it into a support chat).

Open it with any text editor and set these values:

| Line | Your production value | Explanation |
| ---- | --------------------- | ----------- |
| `APP_NAME=DigitalStore` | `APP_NAME="Your Store Name"` | Shown as the site/app name |
| `APP_ENV=production` | keep `production` | Tells the app it is live |
| `APP_KEY=` | keep as-is (set by Part 8) | Encryption key — never make your own |
| `APP_DEBUG=false` | keep `false` | **Critical.** If `true`, crash details are shown to visitors |
| `APP_URL=http://localhost:8000` | `APP_URL=https://yourstore.com` | Your full domain, with `https` |
| `DB_HOST=127.0.0.1` | `localhost` on shared host | Database server |
| `DB_PORT=3306` | keep `3306` | Database port |
| `DB_DATABASE=digital` | your database name (e.g. `yours_db`) | |
| `DB_USERNAME=root` | your DB user (e.g. `yours_user`) | |
| `DB_PASSWORD=` | your **strong** DB password | |
| `SESSION_DRIVER=database` | keep `database` | Sessions stored in DB (recommended) |
| `SESSION_LIFETIME=120` | keep `120` | 120 minutes of logged-in session |
| `SESSION_SECURE_COOKIE=true` | keep `true` | Login cookie only sent over HTTPS |
| `CACHE_STORE=database` | keep `database` | Cache stored in DB |
| `QUEUE_CONNECTION=database` | keep (not used by this app) | |
| `MAIL_MAILER=log` | optional. This app sends **no automatic emails**, so `log` is fine. If you later use email tools, set your SMTP values here | |
| `MAIL_FROM_ADDRESS="hello@example.com"` | your from address, optional | |

Do **NOT** change: `APP_KEY` (set it via the command in Part 8), `SESSION_ENCRYPT`, port numbers, or any `AWS_/REDIS_/MEMCACHED_` lines unless you specifically use those services.

After saving `.env`, always run (from the project folder):
```bash
php artisan config:clear
```
> Your host may show hidden files — if you cannot see `.env` in File Manager, enable "Show Hidden Files (dotfiles)" in its settings.

---

## Part 8. Install Dependencies and Generate the App Key

> These copy-paste commands must be run from the **project root** (the folder that contains `.env`, i.e. `storeapp` on cPanel, or `/var/www/yourstore` on VPS).

### cPanel with Terminal / SSH
Most hosts have "Terminal" in cPanel. Otherwise connect with an SSH client (e.g. PuTTY).
```bash
cd ~/storeapp

# 1. Install PHP packages (the app's engine)
composer install --no-dev --optimize-autoloader

# 2. Generate the secret APP_KEY the first time only
php artisan key:generate --force

# 3. Install front-end assets (optional; the site works without it)
npm install
npm run build

# 4. Make the uploaded images displayable (symlink)
php artisan storage:link
```

### cPanel WITHOUT Terminal (no SSH)
1. On your computer, in the project folder run locally: `composer install --no-dev` (you need Composer installed locally), so the `vendor` folder is created.
2. Zip the whole folder again **including `vendor`** and re-upload/extract.
3. `php artisan key:generate` also needs PHP — use the host's **"Setup PHP"** tool to at least confirm PHP 8.3. Without any PHP terminal you cannot run this step; ask your host's support to run **one** command for you:
   ```bash
   php artisan key:generate --force && php artisan storage:link
   ```
   (or install a tiny SSH/tool — most hosts offer one click).

### VPS
```bash
cd /var/www/yourstore
composer install --no-dev --optimize-autoloader
php artisan key:generate --force
npm install
npm run build
php artisan storage:link
```

---

## Part 9. Build the Database Tables and Create Your Admin

### Create the tables
```bash
php artisan migrate --force
```
This creates all tables. **Do not** run `migrate:fresh` here — that would wipe data.

### Add demo content (optional, then delete later)
```bash
php artisan db:seed --class=DatabaseSeeder --force
```
This fills the shop with sample products/banners so you can see how it looks. Remove them later from the admin panel.

### Create / reset the admin account
```bash
php artisan db:seed --class=AdminSeeder --force
```
This sets the account `admin@digitalstore.com` / password `admin123`. **Log in and change the password right away** (Part 10).

### Adding another admin (or changing the password without editing code)
```bash
php artisan tinker --execute="App\Models\Admin::updateOrCreate(['email'=>'you@yourstore.com'],['name'=>'Your Name','password'=>Illuminate\Support\Facades\Hash::make('YourStrongPassword123'));"
```
Replace the email, name, and password. Run it once and the account exists.

---

## Part 10. First Login and Immediate Security Steps

1. Open `https://yourstore.com/admin/login`
2. Log in with `admin@digitalstore.com` / `admin123`
3. **Change the password immediately.** There is no "change password" page in the admin panel, so use the tinker command in Part 9 with your new password.
4. Under **Admin → Store Settings**, set your real store name, logo, and favicon.
5. Under **Admin → Payment Settings**, set your real bKash/Nagad numbers and toggle active. (Add your payment logos and QR codes here too.)
6. Under **Admin → Social Links**, add your real profiles.
7. Review/rewrite the **Legal Pages** so they match your business.
8. Delete any demo products/banners, or publish your real ones and mark them active.

---

## Part 11. Web Server Setup (pointing the site at the correct folder)

### cPanel / Apache (usually automatic)
- **Option A** (app outside `public_html`): the app ships with `public_html/.htaccess`. Usually nothing more is needed. If you see a blank page, ask support to ensure **mod_rewrite** is on, or from cPanel, set **Document Root** (under Domains) to `public_html`.
- **Option B** (whole project inside `public_html`): use the **root `.htaccess`** that ships with this project — it handles routing, HTTPS, and security automatically. Just make sure `index.php`, `.htaccess`, and the `app/`, `config/`, `public/`, `storage/`, `vendor/` folders are all inside `public_html`.

### VPS with Nginx
Create `/etc/nginx/sites-available/yourstore` with content similar to:

```nginx
server {
    listen 80;
    server_name yourstore.com www.yourstore.com;
    root /var/www/yourstore/public;

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
    }

    location ~ /\.(?!well-known).* { deny all; }
}
```
Then:
```bash
ln -s /etc/nginx/sites-available/yourstore /etc/nginx/sites-enabled/
nginx -t && systemctl reload nginx
```

### VPS with Apache
```apache
<VirtualHost *:80>
    ServerName yourstore.com
    DocumentRoot /var/www/yourstore/public
    <Directory /var/www/yourstore/public>
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog ${APACHE_LOG_DIR}/error.log
</VirtualHost>
```
Then enable and reload Apache. Also install the `php8.3-mysql` extension if the DB connection fails.

### Folder permissions (both setups)
The app needs to write to two folders:
```bash
chmod -R 775 storage bootstrap/cache
```
On cPanel, files uploaded via File Manager usually already have the right permissions.

---

## Part 12. TLS/HTTPS (the secure padlock)

- **cPanel:** enable "AutoSSL / Let's Encrypt" from the SSL section. Rewrites to `https` are usually automatic; set `APP_URL=https://yourstore.com` in `.env` and re-run `php artisan config:clear`.
- **VPS:** install Certbot:
  ```bash
  apt install certbot python3-certbot-nginx
  certbot --nginx -d yourstore.com -d www.yourstore.com
  ```
- After enabling HTTPS, open the site and confirm every image loads with `https://` (check the browser padlock). If images are "mixed content", re-upload images or re-save them in the admin, or fix the storage link (Part 13).

---

## Part 13. Uploaded Files, Storage, and Max Upload Size

- `php artisan storage:link` creates a shortcut so uploaded images (products, banners, logos, QR codes) appear on the site.
- **If storage:link fails** on your host (some shared hosts block symlinks): create a real folder `public_html/storage` and copy everything from `storeapp/storage/app/public` into it. Whenever you upload new images, repeat the copy or set up an automatic copy.
- **Max upload size:** if product/banner images fail to upload, the PHP limit is too small. In cPanel "Select PHP Version" → "Switch to PHP Options", raise:
  - `upload_max_filesize = 64M`
  - `post_max_size = 64M`
- Keep image files reasonable (under ~2–5 MB each) for a fast site.

---

## Part 14. Backups (do this from day one)

### What to back up
1. **The database** — everything important (products, orders, settings).
2. **The project files** — especially `.env`, plus `storage/app/public` (your uploaded images).

### Free, manual backup (weekly)
- cPanel **Backup** tool, or phpMyAdmin → Export the database.
- Or via terminal:
  ```bash
  mysqldump -u yourstore -p yourstore > backup_$(date +%F).sql
  zip -r backup_files.zip storage/app/public .env
  ```
- **Download the backup files to your computer or cloud** (Google Drive/Dropbox). A backup stored on the same server is not a backup.

### Automatic backups (recommended)
- Upgrade a few dollars a month: **CodeGuard** (cPanel widget) or host's "JetBackup / Site backup".
- Or on VPS, add a cron for the `mysqldump` command (e.g. daily at 3 AM). Your cheap VPS supports cron natively.

### How to restore
- **Database:** phpMyAdmin → Import the `.sql` file (delete old tables first if the restore errors), or `mysql -u yourstore -p yourstore < backup.sql`.
- **Files:** upload the zipped `storage/app/public` and `.env` back into place.

> This app has **no scheduled tasks**, so you do **not** need to configure any cron job inside the app.

---

## Part 15. Day-to-Day Managing of the Store

### Selling flow (manual delivery)
1. Customer places an order at checkout → you see it under **Orders → Pending**.
2. **Check your bKash/Nagad app** — the money with the right amount and reference should have arrived.
3. In the admin, open the order → **Verify** (status becomes Verified).
4. **Email the product file/link to the customer's email** (the order detail shows it) — send it yourself from your own email.
5. Back in admin, click **Deliver** (status becomes Delivered). The customer sees the whole timeline when they Track Order.
6. If the payment is wrong/absent → **Reject** and optionally type a reason; the reason is shown to the customer on the tracking page.

### Content updates
- Products, Banners, Categories, Reviews, Payment Settings, Social Links, Store Settings, Legal Pages — all clickable from the left menu in `/admin`. Changes save instantly, no restart needed.

### Order filters
- The Orders page has **All / Pending / Verified / Delivered / Rejected** tabs; the dashboard cards jump straight to the matching list.

### Approving reviews
- Customers submit reviews that are **hidden** by default. In **Admin → Reviews**, click the publish/activate toggle to make a review appear on the product page. Use "featured" to put it on the homepage carousel.

---

## Part 16. Keeping the Site Healthy (updates & maintenance)

### Daily/Weekly
- **Check orders** — don't let customers wait.
- **Verify backups** exist and restore-test occasionally.

### Monthly
- Take an **off-site backup**.
- Delete old/rejected orders or spam reviews if you want a clean panel.
- Check disk space in cPanel.

### When SOMETHING needs an update
- You will rarely need to deploy new versions. If you receive a new version of the app:
  1. Keep the database as-is (do NOT run `migrate:fresh`).
  2. Upload the new files over the old ones (but **keep your `.env`**).
  3. From the project folder: `composer install --no-dev --force` then `php artisan migrate --force` (this adds any new tables/columns without deleting data) then `php artisan config:clear cache:clear view:clear`.
- **Maintenance mode** (e.g. while uploading): `php artisan down` from the project folder, do your work, then `php artisan up`. Visitors see a "be right back" page instead of errors.

---

## Part 17. Security Checklist (do all of these)

- [ ] `APP_ENV=production` and `APP_DEBUG=false` in `.env`
- [ ] `APP_URL` is `https://...` and the SSL padlock shows
- [ ] `APP_KEY` is set (long random string) and locked away in a password manager
- [ ] **Admin password changed** from the default (`admin123`) to a long unique one
- [ ] MySQL database has a long unique password; it is **not** used anywhere else
- [ ] Only you have access to `.env` and your hosting/DNS accounts, all with 2-factor authentication enabled where available
- [ ] `.env` and all app files are **outside** the web-visible folder (`public_html`)
- [ ] Update the admin list each time staff change (Part 9 add/remove: delete old accounts in phpMyAdmin `admins` table or ask a developer)
- [ ] Keep the OS and PHP version updated on a VPS (`apt update && apt upgrade`)
- [ ] Regular off-site backups (Part 14)
- [ ] Review payment instructions regularly so customers always pay to the right number
- [ ] Never paste `.env` contents, DB passwords, or admin credentials into support chat or public messages

### What the app already protects you from
Too many login attempts (auto-lock), fake/edited prices, expired checkout sessions, CSRF form abuse, SQL injection, and XSS — these are built in and need no setup from you.

---

## Part 18. Troubleshooting (start here when something breaks)

| # | Symptom | Likely cause | Fix |
| - | ------- | ------------ | --- |
| 1 | White page / "500 Server Error" | App error (probably config) | Make sure `APP_DEBUG=false` is kept, but for fixing temporarily: ask support to check `storage/logs/laravel.log`, or set `APP_DEBUG=true` ONLY to read the error, fix, then set back to `false`. |
| 2 | "419 Page Expired" when submitting a form | Session/token mismatch (usually from going "Back" or a stale cached page) | Refresh the page and submit again. Clear browser cache. On shared hosting, ensure `SESSION_DRIVER=database` (already set) and that the `sessions` table exists (Part 9 migrate). |
| 3 | "429 Too Many Requests" | Rate limiter (too many login/checkout/track attempts) | Wait 1–2 minutes and retry, OR from the project folder run `php artisan cache:clear` (and `php artisan config:clear`). |
| 4 | Can't log in as admin | Wrong password or locked out | Use the tinker command in Part 9 to reset/change the password; running `php artisan cache:clear` clears the lockout counter. |
| 5 | Logged in but always sent back to `/admin/login` | Session/cookie issue (cookie domain) | Ensure `SESSION_DOMAIN` is empty in `.env`, `SESSION_DRIVER=database`, and your domain is reached once over `https`; clear browser cookies. |
| 6 | Product/banner/logo images broken | Storage link missing | Run `php artisan storage:link`, or create `public/storage` and copy the files (Part 13). |
| 7 | Site loads but on phone looks fine/loads slow | Large images | Re-export images smaller (under ~500 KB) and re-upload. |
| 8 | "No order found" on order tracking | Customer typed the wrong ID, or the ID changed | Track accepts `ORD-0001`, `#ORD-0001`, or `0001`. If it still fails, look up the real ID in Admin → Orders. |
| 9 | Migration fails: "Access denied" / 1044/1045 | Wrong DB credentials in `.env` | Double-check `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` (Part 7), then `php artisan config:clear`. |
| 10 | `composer: command not found` | Composer not installed on the server | Use the host's "Terminal" or install via your host's "Software" tools; or upload a `vendor` folder built locally (Part 8). |
| 11 | "Class .. not found" / red errors right after upload | Vendor folder missing or wrong PHP version | Run `composer install --no-dev`; confirm PHP 8.3+. |
| 12 | Login/checkout forms constantly "expired" | PHP session not saving | Session driver is `database`; ensure migrate ran and the `sessions` table exists. |
| 13 | HTTPS padlock shows "Not secure" / mixed content | Images loading over HTTP | Set `APP_URL` to `https://`, add SSL, re-upload images, and run `php artisan config:clear`. |
| 14 | Site says "In Maintenance" (503) | Maintenance mode left on | From the project folder run `php artisan up`. |
| 15 | Can't upload images | PHP upload size limit | Raise `upload_max_filesize` and `post_max_size` in the host's PHP settings (Part 13). |
| 16 | Nothing happens / site is a default "index" page | Document root wrong or old `index.html` | Confirm web root is the `public` folder content; delete default `index.html`. |
| 17 | "502 Bad Gateway" (VPS) | PHP-FPM stopped | `systemctl restart php8.3-fpm nginx` (ask your host/VPS docs). |
| 18 | Store shows demo/garbage data | Seeder ran, or wrong database | Remove items in admin, or switch `.env` DB settings to your real database and `migrate --force`. |

### A healthy checklist when in doubt
```bash
cd ~/storeapp          # (or /var/www/yourstore)
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```
Then refresh the site. This fixes a surprisingly large number of "it was working and now it's not" problems because it reloads `.env` and clears cached pages.

---

## Part 19. Glossary (plain English)

| Term | Meaning |
| ---- | ------- |
| **Hosting** | A company's computer that runs your site 24/7 |
| **cPanel** | A visual control panel most hosts provide to manage files, databases, SSL |
| **Database / MySQL** | Where the app stores products, orders, and settings |
| **`.env`** | A settings file holding passwords and configuration; lives outside the web folder |
| **Migrations** | Scripts that build/update database tables; run them with `php artisan migrate` |
| **Seeder** | Scripts that insert starting data; run with `php artisan db:seed` |
| **`php artisan`** | The command-line tool of Laravel (your app's engine) |
| **SSL / HTTPS** | Encryption between visitor and server; gives the padlock |
| **DNS / nameservers** | The address book that sends `yourstore.com` to your hosting |
| **SSH / Terminal** | A text window to type commands into the server |
| **Composer / npm** | Tools that install the app's libraries and styling |
| **Maintenance mode** | A built-in "be right back" page while you work |

---

## Part 20. 5-Minute Quick Start (if you already know what you're doing)

```bash
cd /var/www/yourstore                 # your project folder
composer install --no-dev --optimize-autoloader
php artisan key:generate --force
php artisan migrate --force
php artisan db:seed --class=AdminSeeder --force
php artisan storage:link
```
Then:
1. Edit `.env` → your database credentials + `APP_URL=https://yourstore.com` + `APP_DEBUG=false`.
2. `php artisan config:clear`.
3. Open `https://yourstore.com/admin/login`, login, change the password, fill in store/payment/social settings.

Your store is live. Now go read Part 14 (backups) — it only takes two minutes and saves your business.