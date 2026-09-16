# Digital Product Store — Complete Feature List

> A finished, ready-to-use online store for selling digital products (courses, e-books, templates, software, etc.) with a customer-facing website and a separate admin panel. Payments are manual (bKash / Nagad "Send Money"), so there is **no payment gateway API and no automated product delivery**.

---

## 1. Customer-Facing Website (your customers)

### Homepage (single page)
- **Hero carousel** — rotating banner slides, each with:
  - Title and subtitle
  - A blurred "artist's impression" background made from the banner image (solid indigo gradient if the banner has no image)
  - A "Buy / Shop Now" button that links to a product or any website
  - Dot navigation (arrows hidden on phones)
- **Featured products** — horizontally scrollable card strip with quick-view
- **Customer reviews carousel** — admin-approved, featured reviews shown as rotating cards
- **About section** — short store introduction
- **Footer** — store info, quick links, legal links, and your social media icons (Facebook, WhatsApp, Messenger, Telegram, Instagram, TikTok, YouTube, Twitter/X, LinkedIn, Email)
- **Floating support button** — opens a small pop-up showing your contact details (from the same social links)

### All Products page (`/products`)
- Live **search box** (title, subtitle, description)
- **Category filter** dropdown
- Responsive product grid with **pagination**
- Quick-view **modal** for each product (image, price, description, features, reviews, Buy button)

### Product detail page (`/product/{slug}`)
- Large product image, title, subtitle
- Price with **old-price strikethrough** and **discount % badge**
- Availability status badge
- Description with "Read More / Show Less"
- Key features list
- **Buy Now** (goes to checkout) and **Track Order** buttons
- Customer reviews (only **admin-approved** reviews are shown to the public)
- **"Write a Review" form** — customers can submit name + 1–5 star rating + text. Submissions go to the admin panel hidden; they appear publicly **only after an admin approves them**.

### Checkout (`/checkout/{id}`)
- Choose payment method: **bKash** or **Nagad** (only enabled methods appear)
- Shows the official number, account type, step-by-step "Send Money" instructions, QR code, and payment logo
- Payment details form: customer email, sender number (11-digit BD mobile), transaction ID
- **Server-side price validation** — the customer can never change the price
- **30-minute session expiry** with a **live countdown timer** — after expiry the order cannot be submitted
- Order summary sidebar (price, discount, total)

### Order tracking (`/order/track`)
- Customer enters their Order ID (e.g. `ORD-0001`, `#ORD-0001`, or just `0001`)
- Shows live status with a **visual timeline**: Pending → Verified → Delivered (or **Rejected**)
- If rejected, shows the **rejection reason** entered by the admin
- Rate-limited to prevent abuse

### Legal / info pages
- Privacy Policy
- Terms & Conditions
- Refund Policy
- About
- All content editable from the admin panel, no coding needed

### Design & usability
- Fully **responsive mobile design** (hamburger menu, stacking grids, tap-friendly buttons)
- Consistent store branding (name, logo, favicon) — changed in admin, applied everywhere instantly

---

## 2. Admin Panel (`/admin`)

> The **only publicly reachable admin page is the login page** (`/admin/login`). Everything else requires an admin login.

### Login
- Email + password, passwords stored securely hashed
- **Automatic lockout** after 5 failed attempts ("Too many login attempts") that unlocks after 1 minute
- Separate admin session system, no public registration

### Dashboard
- **Clickable statistics cards** — Total Products, Active Products, Total/Pending/Verified/Delivered/Rejected Orders, Total Revenue, Total Reviews
  - Clicking a card jumps straight to the matching filtered list
- Recent orders and recent reviews
- Quick actions (Add product, Add banner, etc.)

### Products
- Add / edit / delete products
- Fields: title, subtitle, category, price, old price (for discount), description, feature bullets, product image, availability status
- **Toggle active/inactive** and **drag-style reordering** for featured display

### Banners
- Add / edit / delete homepage carousel slides
- Title, subtitle, button text, action (link to a product or any external URL), banner image, order, active toggle

### Categories
- Optional. Create/rename/delete categories used by the product filter.

### Orders (order flow)
- List with filter tabs: **All / Pending / Verified / Delivered / Rejected**
- Order detail: customer info, payment info (method, sender number, transaction ID), product, price, order notes
- Admin actions:
  - **Verify** (once you confirm the payment arrived)
  - **Deliver** (mark product sent)
  - **Reject** (optionally with a reason that is shown to the customer on the tracking page)
- Optional private admin notes per order

### Reviews
- See **all** submitted reviews, including ones that are not yet public
- **Approve/activate** a review (this publishes it on the product page)
- Mark a review as **featured** (shows it in the homepage carousel)
- Delete reviews

### Payment Settings
- bKash and Nagad each: account number, account type (Personal/Merchant), **Send Money instructions**, QR code image, payment **logo** image, enable/disable

### Social Links
- Set up your pages for all 10 supported platforms (used in footer, support pop-up, and contact info)

### Store Settings
- Store name, logo, favicon — applied across the whole site automatically

### Legal Pages editor
- Edit the text of Privacy Policy, Terms, Refund Policy, and About page (no HTML knowledge needed)

---

## 3. Payment & Delivery Model (important)

```
Customer → Checkout → Submits order → Status PENDING
    → Admin checks bKash/Nagad app that the money arrived → VERIFIED
    → Admin emails the product file/link to the customer manually → DELIVERED
```

- **No automated email** is ever sent with the product.
- The app never auto-delivers anything after payment.
- Even if a customer finds the career checkout page, they cannot bypass payment because a human admin verifies every order.
- Banners, products, or payment settings can be switched on/off instantly from the admin panel.

---

## 4. Security Built Into the App

- Separate **admin login system** with hashed passwords, no public admin signup
- **Rate limiting** everywhere: admin login, checkout submission, order tracking, review submission
- **Checkout session expiry** (30 minutes) and **server-side price validation**
- **CSRF protection** on all forms, **SQL injection protection** via Eloquent, **XSS protection** via escaped Blade output
- **File upload validation** (allowed file type + size limit) for images, logos, QR codes
- Sessions stored in the **database** (safer than files on shared hosting)
- Designed for `APP_DEBUG=false` in production so errors never leak to visitors

### Default admin credentials (change immediately, see the Production Guide)

| Field    | Value                 |
| -------- | --------------------- |
| Email    | `admin@digitalstore.com` |
| Password | `admin123`              |

---

## 5. Technology Behind the Scenes (short version)

| Component | Used |
| --------- | ---- |
| Framework | Laravel 13 (PHP) |
| PHP version | 8.3+ (tested on 8.5) |
| Database | MySQL (default database name `digital`) |
| Server-side rendering | Laravel Blade + inline CSS |
| Frontend build | npm (provides base styling/scripts; the site looks fine even without a rebuild) |
| Sessions / cache / queue | Database |
| Scheduled tasks | None — this app needs **no cron jobs** |

---

## 6. Useful Artisan Commands (run from the project folder)

| Command | What it does |
| ------- | ------------ |
| `php artisan serve` | Runs the site locally on `http://127.0.0.1:8000` for testing |
| `php artisan migrate:fresh --seed` | **Wipes the whole database** and rebuilds it with demo data |
| `php artisan db:seed --class=AdminSeeder` | Creates/resets only the admin account |
| `php artisan storage:link` | Makes uploaded images display (must run once after install) |
| `php artisan config:clear` | Reloads the `.env` settings after you edit them |
| `php artisan cache:clear` | Clears cached data (also clears stuck login "too many attempts" counters) |
| `php artisan view:clear` | Clears compiled page templates |
| `php artisan down` / `php artisan up` | Maintenance mode: show a "be back soon" page / open the site |

---

For a complete step-by-step explanation of hosting, configuring the `.env` file, adding admins, backups, security, and fixing every common problem, see **`PRODUCTION-GUIDE.md`** in this same folder.