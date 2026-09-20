# Nigerian Kitchen Platform Project Brief

You are a senior Laravel engineer. Build a production-ready website and ordering platform for a Nigerian kitchen serving customers in Nigeria and the diaspora: UK, US, Canada, and Europe. Work in phases, and after each phase report what was built and how to run and test it. Ask before making major assumptions not covered here.

## 1. Tech Stack

- Laravel 11, PHP 8.2+, MySQL, and Vite.
- Frontend: Blade, Tailwind CSS, Alpine.js. Livewire is allowed for cart and checkout.
- Admin: Filament v3 protected by roles and permissions.
- Auth: Laravel Breeze for customers and Spatie Laravel Permission with roles `super_admin`, `manager`, and `kitchen_staff`.
- PDF invoices: `barryvdh/laravel-dompdf`.
- Queues: database driver, with Redis optional, and scheduler reminders.
- Images: Spatie Media Library with WebP conversions.
- SEO: Spatie sitemap plus custom meta and JSON-LD helpers.
- Tests: Pest or PHPUnit feature tests for ordering, payment webhooks, invoices, and quote workflows.

## 2. Core Customer Services

1. Food ordering with menu browsing, cart, delivery, pickup, and checkout.
2. Party and event catering with packages, guest count, event date, venue, dietary notes, quote, deposit, and final payment workflow.
3. Special requests for off-menu dishes with description, quantity, date, budget, reference image, admin quote, acceptance, and secure payment links.
4. Combo deals with bundled items and optional customizable slots.
5. Phase 2 extras: meal prep, weekly subscriptions, gift cards, and frozen or packaged foods shipped to the diaspora behind feature flags.

## 3. Public Pages

Home, menu, menu item detail, combos, catering, special requests, cart, checkout, confirmation, order tracking, customer account, testimonials, about, FAQ, contact, gallery, optional blog, legal pages, and an editable footer. Include a floating WhatsApp order button with an admin-editable number.

The menu needs category tabs, search, filters for spice, vegetarian status, and price, plus Popular, New, and Spicy tags. Item details need images, descriptions, variants, add-ons, allergens, spice level, and related items.

Checkout must support guest or authenticated users, delivery or pickup, delivery zone and fee, promo code, order notes, scheduled date/time slot, and order confirmation.

## 4. Payments and Currency

Use a common payment gateway interface and strategy pattern for:

- Paystack in NGN for Nigeria.
- Stripe in GBP, USD, CAD, and EUR for diaspora customers.
- Bank transfer with proof upload and manual confirmation.
- Toggleable pay-on-delivery or pay-on-pickup by zone.

Use NGN as base currency with configurable display currencies, exchange rates, or per-currency item price overrides. Webhooks must verify signatures and be idempotent. Store payment records and support refunds and partial payments. Catering needs deposits and balance payment links.

## 5. Lifecycle and Notifications

Order statuses: Pending Payment, Paid, Confirmed, Preparing, Ready for Pickup, Out for Delivery, Delivered/Completed, Cancelled, and Refunded.

Catering and special-request statuses: Submitted, Under Review, Quote Sent, Accepted, Deposit Paid, In Preparation, Completed, and Declined.

Use queued Laravel notifications for email and database notifications. Notify on status changes, payment success/failure, quotes, and invoice issuance. Add an SMS/WhatsApp channel abstraction with a stub driver ready for Termii, Twilio, or WhatsApp Cloud API. Keep notification logs and allow admin-edited email templates where practical.

## 6. Invoicing

Generate an invoice for each paid order and allow manual invoices for catering, special requests, and offline orders. Support custom line items, discounts, delivery fees, tax/VAT, notes, due dates, invoice numbering such as `INV-2026-0001`, Draft/Sent/Paid/Overdue/Void statuses, PDF download, email delivery, company settings, bank details, and payment links.

## 7. Admin Panel

Provide dashboard metrics for today’s orders, revenue by currency, pending requests, top-selling items, and charts.

Admin resources and settings must cover menu categories/items, variants, add-ons, images, availability, stock/daily limits, tags, combos, catering packages/requests, special requests, orders, kitchen tickets, delivery notes, customers, addresses, payments/refunds, invoices, delivery zones, pickup locations, time slots, lead times, holidays, promo codes, testimonials, CMS content, site settings, SEO, analytics fields, users, roles, permissions, activity logs, and newsletter CSV export.

## 8. SEO and Performance

Add unique title, meta description, canonical, Open Graph, and Twitter metadata. Generate Restaurant/LocalBusiness, Menu/MenuItem, Product/Offer, Review/AggregateRating, FAQPage, and BreadcrumbList JSON-LD. Generate sitemap and robots files. Use lazy WebP images with alt text, caching, eager loading, mobile-first responsive design, accessibility basics, and a Lighthouse target of 90+.

## 9. Security and Quality

Use Form Requests, policies/gates, CSRF, rate limits, honeypot or reCAPTCHA on public forms, encrypted gateway secrets, webhook verification, audit logging, integer minor-unit money values, database transactions, service/action classes, documented environment variables, Laravel Pint, and PHPDoc on services.

## 10. Seed Data

Create idempotent factories and seeders for roles, a default super admin, roughly 30 Nigerian menu items, variants, add-ons, five or more combos, four catering packages, delivery zones, time slots, promo codes, testimonials, FAQs, gallery placeholders, customers, orders in different statuses, invoices, catering requests, special requests, site settings, CMS content, footer content, and legal placeholders.

Suggested menu items include Jollof Rice, Fried Rice, Ofada Rice and Ayamase, Egusi Soup, Efo Riro, Ogbono, Oha, Banga Soup, Pounded Yam, Amala with Ewedu/Gbegiri, Eba, Semo, Suya, Asun, Grilled Fish, Pepper Soup, Moi Moi, Akara, Puff-Puff, Chin Chin, Meat Pie, Dodo, Nkwobi, Isi Ewu, Zobo, and Chapman.

## 11. Deliverables

1. Complete Laravel project code, migrations, models, relationships, and factories.
2. README with setup, migrations, storage, queues, scheduler, payment webhooks, and deployment notes.
3. Postman collection or route list for APIs.
4. Feature tests for cart/checkout, payment webhooks, status notifications, invoice generation, and catering quote flow.
5. Short admin user guide in Markdown.

## Required Build Order

- Phase 1: project setup, auth, roles, database schema, migrations, models, and settings.
- Phase 2: Filament admin resources and CMS.
- Phase 3: public site, home, menu, combos, item pages, cart, and checkout.
- Phase 4: payments, order lifecycle, and notifications.
- Phase 5: catering, special requests, and quote workflow.
- Phase 6: invoicing, PDF, and email.
- Phase 7: SEO, performance, security hardening, and tests.
- Phase 8: seeders, README, admin guide, and final QA checklist.

## Design Direction

Use a warm, vibrant, clean, and premium visual direction inspired by Nigerian colors and cuisine: deep green, gold/amber, and warm reds. Food photography should be prominent in the hero experience.
