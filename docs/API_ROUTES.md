# His Grace Kitchen LTD — API & Route Catalog

This document outlines all public endpoints, checkout flows, payment webhooks, and administrative endpoints.

---

## 1. Public Storefront & Catalog Endpoints

### `GET /`
- **Route Name**: `home`
- **Description**: Returns homepage with hero section, signature dishes, combos, catering packages, testimonials, FAQs, and WhatsApp quick-contact.
- **Response**: `200 OK` (HTML with Schema.org Restaurant & FAQPage JSON-LD).

### `GET /menu`
- **Route Name**: `menu.index`
- **Description**: Paginated catalog of authentic dishes with category, spice, search, and dietary filters.
- **Query Parameters**:
  - `category` *(optional, string)*: Slug of menu category (e.g. `rice-grains`, `soups-swallows`).
  - `search` *(optional, string)*: Search keyword matching dish name or description.
  - `spice` *(optional, string)*: `Mild`, `Medium`, `Hot`, or `Extra Hot`.
  - `vegetarian` *(optional, boolean)*: `1` for vegetarian-only items.
  - `page` *(optional, integer)*: Pagination page number.

### `GET /menu/{slug}`
- **Route Name**: `menu.show`
- **Description**: Detailed dish page with variants, available add-ons, allergens, spice indicators, and related dishes.
- **Parameters**: `slug` (e.g. `smoky-party-jollof-rice`).
- **Response**: `200 OK` (HTML with Schema.org MenuItem & BreadcrumbList JSON-LD).

### `GET /combos`
- **Route Name**: `combos.index`
- **Description**: Curated bundle packages (Owambe Party Box, Swallow Feast, Suya Platter, etc.).

### `GET /catering`
- **Route Name**: `catering.index`
- **Description**: Catering packages overview and interactive proposal quote request form.

### `POST /catering`
- **Route Name**: `catering.store`
- **Rate Limit**: 15 requests per minute
- **Payload (`application/x-www-form-urlencoded` or `multipart/form-data`)**:
  ```json
  {
    "customer_name": "Chief Babatunde Adeleke",
    "customer_email": "adeleke@example.com",
    "customer_phone": "+2348031234567",
    "catering_package_id": 1,
    "guest_count": 150,
    "event_date": "2026-11-20",
    "venue": "Landmark Event Centre, Victoria Island, Lagos",
    "dietary_notes": "Live suya BBQ station requested. 10 Halal options.",
    "website_hp": ""
  }
  ```
- **Response**: `302 Redirect` to `catering.show` with generated reference `CAT-XXXXX`.

### `GET /catering/{reference}`
- **Route Name**: `catering.show`
- **Description**: Customer proposal review page showing event specs, quote breakdown, deposit required, and payment actions.

### `POST /catering/{reference}/accept`
- **Route Name**: `catering.accept`
- **Payload**:
  ```json
  {
    "payment_type": "deposit", // or "balance"
    "gateway": "paystack" // or "stripe", "bank_transfer"
  }
  ```
- **Response**: `302 Redirect` to payment gateway or bank transfer confirmation page.

### `GET /special-request`
- **Route Name**: `special-requests.create`
- **Description**: Custom off-menu dish request form with market ingredient description.

### `POST /special-request`
- **Route Name**: `special-requests.store`
- **Rate Limit**: 15 requests per minute
- **Payload**:
  ```json
  {
    "customer_name": "Amaka Nwosu",
    "customer_email": "amaka@example.com",
    "customer_phone": "+2348029998888",
    "description": "5L pot of authentic Calabar Native Fisherman Soup with live periwinkles and giant tiger prawns.",
    "quantity": 1,
    "needed_by": "2026-10-15",
    "budget": 75000,
    "reference_image_url": "",
    "website_hp": ""
  }
  ```
- **Response**: `302 Redirect` to `special-requests.show` with reference `REQ-XXXXX`.

### `GET /special-request/{reference}`
- **Route Name**: `special-requests.show`
- **Description**: Review page for custom off-menu quote with immediate payment action.

---

## 2. Cart, Checkout & Orders

### `GET /checkout`
- **Route Name**: `checkout.index`
- **Description**: Checkout page displaying delivery zones, delivery fees, time slot options, and gateway selection.

### `POST /checkout`
- **Route Name**: `checkout.store`
- **Rate Limit**: 20 requests per minute
- **Payload**:
  ```json
  {
    "fulfilment_type": "delivery", // or "pickup"
    "customer_name": "Folake Adeleke",
    "customer_email": "customer@example.com",
    "customer_phone": "+2348031234567",
    "delivery_zone_id": 1,
    "delivery_address": {
      "street": "14 Admiralty Way",
      "city": "Lekki Phase 1",
      "state": "Lagos",
      "country": "Nigeria"
    },
    "payment_gateway": "paystack", // "paystack", "stripe", "bank_transfer", "pay_on_delivery"
    "currency": "NGN",
    "notes": "Extra pepper sauce please.",
    "items": [
      {
        "type": "item",
        "id": 1,
        "quantity": 2,
        "variant": "Jollof with Smoked Chicken",
        "add_ons": ["Fried Dodo"]
      }
    ]
  }
  ```
- **Server Verification**: The server independently queries `MenuItem` and `Combo` database tables to re-verify prices, calculate delivery fees by zone, compute subtotal and total, and issue order record `NK-YYYY-XXXXX`.
- **Response**: `302 Redirect` to gateway or `orders.show`.

### `GET /orders/{order_number}`
- **Route Name**: `orders.show`
- **Description**: Receipt with 4-stage visual timeline status tracker (Pending Payment -> Paid / Confirmed -> Preparing -> Out for Delivery -> Delivered).

### `GET /orders/{order_number}/invoice`
- **Route Name**: `orders.invoice`
- **Description**: Downloads customer PDF invoice directly from order receipt.

### `GET /orders/{order_number}/bank-transfer`
- **Route Name**: `orders.bank-transfer`
- **Description**: Displays official corporate bank accounts (GTBank & Zenith Bank) and payment reference for offline transfer settlement.

### `GET|POST /track-order`
- **Route Name**: `orders.track`
- **Rate Limit**: 60 requests per minute
- **Description**: Self-service lookup by order number and email.

---

## 3. Invoices (PDF & Stream)

### `GET /invoices/{number}/download`
- **Route Name**: `invoices.download`
- **Description**: Generates and downloads PDF with attachment header `inline; filename="invoice-INV-XXXX.pdf"`.

### `GET /invoices/{number}/stream`
- **Route Name**: `invoices.stream`
- **Description**: Streams PDF directly inside the browser viewport.

---

## 4. Payment Webhooks

### `POST /webhooks/paystack`
- **Route Name**: `webhooks.paystack`
- **CSRF Exempt**: Configured in `bootstrap/app.php`
- **Headers**:
  - `x-paystack-signature`: HMAC-SHA512 hash of the payload using `PAYSTACK_SECRET_KEY`.
- **Payload Event**: `charge.success`
  ```json
  {
    "event": "charge.success",
    "data": {
      "reference": "pstk_ref_xxxx",
      "status": "success",
      "amount": 1600000,
      "currency": "NGN",
      "paid_at": "2026-09-20T21:00:00.000Z",
      "metadata": {
        "order_number": "NK-2026-00101"
      }
    }
  }
  ```
- **Behavior**: Idempotently marks Order status to `paid`, creates Payment audit record, and triggers `InvoiceService::createFromOrder()`.
- **Response**: `200 OK` (`{"status": "ok"}`).

### `POST /webhooks/stripe`
- **Route Name**: `webhooks.stripe`
- **CSRF Exempt**: Configured in `bootstrap/app.php`
- **Headers**:
  - `stripe-signature`: Stripe webhook signing signature.
- **Payload Event**: `checkout.session.completed` / `payment_intent.succeeded`
  ```json
  {
    "type": "checkout.session.completed",
    "data": {
      "object": {
        "id": "cs_test_xxxx",
        "payment_intent": "pi_xxxx",
        "payment_status": "paid",
        "amount_total": 11000,
        "currency": "gbp",
        "client_reference_id": "NK-2026-00102"
      }
    }
  }
  ```
- **Response**: `200 OK` (`{"status": "ok"}`).

---

## 5. SEO & Search Engine Endpoints

### `GET /sitemap.xml`
- **Route Name**: `sitemap`
- **Content-Type**: `application/xml`
- **Description**: Auto-generates or serves `public/sitemap.xml` listing all active dishes, combos, catering packages, and storefront pages.

### `GET /robots.txt`
- **Content-Type**: `text/plain`
- **Description**: Search crawler guidelines allowing public pages, disallowing `/admin/`, and referencing `sitemap.xml`.

