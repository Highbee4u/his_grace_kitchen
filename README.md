# His Grace Kitchen LTD — Production-Ready Culinary & Diaspora Ordering Platform

[![Laravel 11](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP 8.3](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square&logo=php)](https://php.net)
[![Filament v3](https://img.shields.io/badge/Filament-v3-F59E0B?style=flat-square)](https://filamentphp.com)
[![Tailwind CSS v3](https://img.shields.io/badge/TailwindCSS-v3-38B2AC?style=flat-square&logo=tailwind-css)](https://tailwindcss.com)
[![Tests Passing](https://img.shields.io/badge/Tests-58%20Passed-10B981?style=flat-square)](https://github.com)

A high-performance, feature-rich web and ordering platform engineered for an authentic Nigerian culinary kitchen catering to domestic customers (Nigeria) and the global diaspora (United Kingdom, United States, Canada, and Europe).

Built with **Laravel 11**, **Filament v3**, **Blade/Tailwind/Alpine.js**, **Barryvdh DomPDF**, and multi-gateway payment processing (**Paystack**, **Stripe**, **Bank Transfer**, **Pay on Delivery**).

---

## 🌟 Key Architecture & Highlights

### 1. Multi-Currency Minor-Unit Financial Engine
All monetary values across dishes, variants, delivery zones, order items, quotes, and invoices are calculated and persisted strictly as integer minor units (kobo/cents). Prevents floating-point rounding errors across 5 supported currencies:
- **NGN (₦)** — Domestic Nigerian transactions via Paystack
- **GBP (£)** — UK Diaspora orders via Stripe
- **USD ($)** — US & International orders via Stripe
- **CAD ($)** — Canadian diaspora orders via Stripe
- **EUR (€)** — European diaspora orders via Stripe

### 2. Multi-Gateway Payment Strategy Pattern
Decoupled payment orchestration via `PaymentGatewayInterface` and `PaymentGatewayManager`:
- `PaystackPaymentGateway`: Inline checkout and redirect flow for NGN debit cards, USSD, and bank transfers.
- `StripePaymentGateway`: International multi-currency credit/debit card processing.
- `BankTransferGateway`: Manual settlement with bank account instructions (Guaranty Trust Bank & Zenith Bank) and payment receipt tracking.
- `PayOnDeliveryGateway`: Configurable cash or POS on delivery scoped by delivery zone.
- `WebhookController`: Cryptographically verified, idempotent webhook listeners for `/webhooks/paystack` and `/webhooks/stripe`.

### 3. Complete Storefront Customer Experience
- **Interactive Full Catalog**: 5 culinary categories, 23+ authentic dishes (Smoky Party Jollof, Egusi Soup, Efo Riro, Ofada & Ayamase, Suya, Banga, Asun, Nkwobi, Chapman, etc.).
- **Dish Details & Customization**: Protein selection, portion size variants, extra add-ons (fried plantain, boiled egg, assorted meat), and spicy heat levels.
- **Persistent Alpine Cart Drawer**: LocalStorage-synced reactive shopping cart with dynamic currency switcher.
- **Authoritative Server Pricing**: Client cart items are validated against the database on checkout to prevent client-side price manipulation.
- **Event Catering Proposals**: Automated quote, deposit (e.g. 50%), date lock, and balance payment workflow.
- **Custom Off-Menu Special Requests**: Tailored off-menu dish inquiries (e.g. Fisherman soup, Ofe Owerri, Tuwo) with quote approval and secure payment link.
- **Order Tracking**: Self-service 4-stage visual timeline tracker (`/track-order`) with lookup by order number and email.

### 4. Filament v3 Administration Panel
Role-based administrative dashboard at `/admin` powered by Spatie Laravel Permission:
- **5 Navigation Groups**: Orders & Fulfillment, Kitchen & Menu Catalog, Events & Special Requests, Operations & Finance, System & Settings.
- **13 Customized Resources**: Orders, Kitchen Tickets, Menu Items, Categories, Add-ons, Combos, Catering Packages, Catering Requests, Special Requests, Invoices, Delivery Zones, Site Settings, Users.
- **Staff Access Tiers**:
  - `super_admin`: Full system control and user management.
  - `manager`: Menu, catering quotes, pricing, delivery zones, and invoice issuance.
  - `kitchen_staff`: Live kitchen tickets, prep statuses, and dispatching.

### 5. Automated PDF Invoicing (`barryvdh/laravel-dompdf`)
- Zero external CSS dependency — pure, self-contained inline DomPDF styles.
- Auto-generated upon order payment (`INV-YYYY-XXXXX`).
- Line item tables, discounts, delivery fees, settlement bank accounts, and invoice download/stream endpoints (`/orders/{order_number}/invoice`, `/invoices/{number}/download`).
- Queued customer email delivery with PDF invoice attachments.

### 6. Search Engine Optimization & Structured Data
- Schema.org **Restaurant / LocalBusiness** JSON-LD.
- Schema.org **MenuItem & Product / Offer** JSON-LD.
- Schema.org **FAQPage** and **BreadcrumbList** JSON-LD.
- Canonical tags, Open Graph meta tags (`og:title`, `og:image`, `og:url`), and Twitter Cards.
- Dynamic XML sitemap generator (`php artisan sitemap:generate` or `/sitemap.xml`) via `spatie/laravel-sitemap`.
- Bot honeypot protections on catering and special request inquiry forms.

---

## 🚀 Quickstart Installation Runbook

### Prerequisites
- PHP 8.2 or 8.3 with extensions: `pdo`, `mbstring`, `openssl`, `curl`, `json`, `gd`
- Composer 2.x
- Node.js 18+ & NPM
- SQLite (default) or MySQL 8.0+

### Step-by-Step Setup

```bash
# 1. Clone repository
git clone https://github.com/your-username/africankitchen.git
cd africankitchen

# 2. Install PHP dependencies
composer install

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Set up database (SQLite is configured by default)
touch database/database.sqlite
php artisan migrate --seed

# 5. Link storage for media uploads
php artisan storage:link

# 6. Generate XML sitemap
php artisan sitemap:generate

# 7. Install & build frontend assets
npm install
npm run build

# 8. Start local development server
php artisan serve
```

The application is now accessible at `http://localhost:8000`.

---

## 👥 Default Credentials

| Role | Email | Password | Access Area |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `admin@africankitchen.test` | `password` | `/admin` (Full Access) |
| **Operations Manager** | `manager@africankitchen.test` | `password` | `/admin` (Operations & Quotes) |
| **Head Line Chef** | `kitchen@africankitchen.test` | `password` | `/admin` (Kitchen Tickets) |
| **Customer** | `customer@example.com` | `password` | Storefront & `/profile` |

---

## ⚙️ Environment Configuration

Add your payment gateway credentials and business info in `.env`:

```ini
APP_NAME="His Grace Kitchen LTD"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration (SQLite default; uncomment for MySQL)
DB_CONNECTION=sqlite
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=africankitchen
# DB_USERNAME=root
# DB_PASSWORD=

# Currency Configuration
APP_BASE_CURRENCY=NGN

# Payment Gateways (Leave empty or test keys in sandbox)
PAYSTACK_PUBLIC_KEY=your_paystack_public_key
PAYSTACK_SECRET_KEY=your_paystack_secret_key
PAYSTACK_PAYMENT_URL=https://api.paystack.co

STRIPE_KEY=your_stripe_public_key
STRIPE_SECRET=your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=your_stripe_webhook_secret

# Bank Transfer Settlement Details
BANK_NAME="Guaranty Trust Bank (GTBank)"
BANK_ACCOUNT_NAME="His Grace Kitchen LTD Limited"
BANK_ACCOUNT_NUMBER="0123456789"

# Queue Driver
QUEUE_CONNECTION=database
```

---

## 💳 Payment Gateway & Webhook Testing

### Paystack (NGN - Domestic)
- When `PAYSTACK_SECRET_KEY` is omitted in development/testing, the driver gracefully simulates approval redirects and webhook ingestion.
- Production webhook endpoint: `POST https://yourdomain.com/webhooks/paystack` (CSRF-exempted, verifies `x-paystack-signature`).

### Stripe (GBP / USD / CAD / EUR - Diaspora)
- Multi-currency diaspora orders trigger Stripe Checkout sessions.
- Production webhook endpoint: `POST https://yourdomain.com/webhooks/stripe` (CSRF-exempted, verifies `stripe-signature`).

### Local Webhook Forwarding with CLI
```bash
# Stripe CLI
stripe listen --forward-to localhost:8000/webhooks/stripe

# Paystack CLI / Ngrok
ngrok http 8000
# Set webhook URL in Paystack Dashboard to: https://xyz.ngrok.io/webhooks/paystack
```

---

## 🛠️ Background Workers & Maintenance

### Queue Worker
The application queues email notifications, invoice dispatch, and PDF generation:
```bash
php artisan queue:work --tries=3 --timeout=90
```

### Scheduled Tasks (Cron)
Add the standard Laravel scheduler entry to your server's crontab:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Refresh XML Sitemap
Regenerate search engine sitemap for new dishes and combos:
```bash
php artisan sitemap:generate
```

---

## 🧪 Automated Testing Suite

The application includes 58 comprehensive Unit and Feature tests with 200 assertions covering:
- Public catalog browsing and category filtering
- Multi-item checkout and authoritative pricing recalculation
- Paystack & Stripe webhook state transitions
- Bank transfer instruction rendering
- Catering proposal review, 50% deposit lock, and balance completion
- Special request custom quote workflow
- Barryvdh DomPDF invoice generation and PDF streaming
- SEO tags, JSON-LD structured data, XML sitemap, and honeypot protection

```bash
# Run complete test suite
php artisan test

# Run tests with code coverage
php artisan test --coverage

# Run specific feature test
php artisan test --filter=CheckoutTest
php artisan test --filter=SeoTest
php artisan test --filter=InvoiceTest
```

### Code Style (Laravel Pint)
```bash
vendor/bin/pint --format agent
```

---

## 📂 Documentation Directory

Detailed operational documentation is available in the `docs/` folder:
- [Admin User Guide](file:///Users/user/Projects/LaravelProjects/Africankitchen/docs/ADMIN_GUIDE.md) — Staff runbook for orders, kitchen tickets, quotes, and PDF invoicing.
- [API & Route Catalog](file:///Users/user/Projects/LaravelProjects/Africankitchen/docs/API_ROUTES.md) — Comprehensive HTTP routes, webhooks, and parameters.
- [Project Progress & Audit](file:///Users/user/Projects/LaravelProjects/Africankitchen/docs/PROJECT_PROGRESS.md) — Build phase milestones and architectural sign-off.
