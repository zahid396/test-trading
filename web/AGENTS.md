# Digital Product E-commerce Store

A complete Laravel digital product store with a customer-facing single-page website and a fully separated admin panel. Uses MySQL, Laravel Blade, Eloquent ORM, and Laravel session-based authentication.

## Setup

```sh
composer install
npm install
npm run build
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

## Docker & Render deployment

The Laravel app lives in the `web/` subfolder of this repo. Docker/`render.yaml` config sits at the repo ROOT (Render builds from the repo root):

- `Dockerfile` - multi-stage: Node 22 (Vite build) + `php:8.4-apache` (do NOT downgrade to 8.3 - Symfony 8.1 needs PHP >= 8.4.1)
- `docker/start.sh` - waits for DB, `migrate --force`, seeds ONCE if empty, `optimize`, starts Apache
- `render.yaml` - Laravel web service only. Production DB is the EXTERNAL Aiven MySQL in `web/.env`; the password (`DB_PASSWORD`) is a Render secret (`sync: false`), never committed. `docker-compose.yml` is local-only (throwaway MySQL) and is NOT used in production.

```sh
docker compose up --build            # local test -> http://localhost:8080
docker build -t digitalstore .       # manual build from repo root
```

Render deploy: push repo -> New Blueprint -> set secrets `APP_KEY`, `APP_URL`, `DB_PASSWORD` (all from `web/.env`; render.yaml already contains the Aiven host/port/db/user). Uploads persist on the `laravel-storage` disk mounted at `/var/www/html/web/storage/app/public`. If Aiven enforces TLS, also add `DB_URL` (Render secret) as `mysql://avnadmin:PASSWORD@HOST:PORT/defaultdb?sslmode=require`.

## Default Admin Credentials

- Email: `admin@digitalstore.com`
- Password: `admin123`

**IMPORTANT**: Change these credentials immediately after deployment via the MySQL database (there is no admin registration page).

## Database

- MySQL database name: `digital` (config in `.env`)
- All migrations in `database/migrations/`

## Architecture

### Customer Website (`/`)
- Single page with Hero carousel, Featured Products, Reviews, About
- `/products` - all products with search/filter
- `/product/{slug}` - product details page
- `/checkout/{id}` - checkout with bKash/Nagad, 30-min session expiry
- `/order/track` - order tracking
- `/privacy-policy`, `/terms`, `/refund-policy`, `/about`

### Admin Panel (`/admin`)
- `/admin/login` - login (THIS is the only public admin route)
- `/admin/dashboard` - statistics dashboard
- `/admin/products`, `/admin/banners`, `/admin/reviews`, `/admin/orders`
- `/admin/payment-settings`, `/admin/social-links`, `/admin/store-settings`
- `/admin/legal-pages`, `/admin/categories`
- All routes except login require `admin.auth` middleware (redirect to login if unauthenticated)

## Order Flow (MANUAL DELIVERY - NO AUTOMATED EMAIL)

```
Customer -> Checkout -> Submit Order -> PENDING
  -> Admin verifies payment -> VERIFIED
  -> Admin manually emails product -> Admin marks DELIVERED
```

**THERE IS NO AUTOMATIC DIGITAL PRODUCT EMAIL DELIVERY.**
The system NEVER auto-sends the product after payment. Admin manually sends it and marks it delivered.

## Key Security Features

- Admin guard (`admin`) separate from web/users guard
- Password hashing via `Hash`
- No public admin registration
- Server-side price validation (never trusts frontend price)
- Checkout sessions expire after 30 minutes
- MySQL session driver
- CSRF protection
- Rate limiting on login/checkout/order-tracking
- File upload MIME + size validation
- Eloquent (SQL injection protection) + Blade escaping (XSS protection)
- `APP_DEBUG=false` in production

## Models & Relationships

- Product belongsTo Category, hasMany Reviews/Orders
- Order belongsTo Product, hasMany OrderNotes
- Review belongsTo Product
- Banner belongsTo Product (action product)
- CheckoutSession belongsTo Product

## Global View Data

The `AppServiceProvider` shares `$storeSettings`, `$paymentMethods`, `$socialLinks` with every view. Admin store/logo/favicon/payment changes reflect site-wide automatically.

## Tech Notes

- Customer & admin views use inline CSS (no build-time Tailwind dependency for display)
- `npm run build` still works for the base `app.css`/`app.js` assets
- Categories, payment settings, and social links are optionally managed in admin

## Artisan Commands

```sh
php artisan db:seed --class=AdminSeeder   # create/reset admin
php artisan migrate:fresh --seed          # full reset with demo data
```
