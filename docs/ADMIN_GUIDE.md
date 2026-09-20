# His Grace Kitchen LTD — Staff & Administrator Operating Guide

This user guide provides operational instructions for managers, culinary staff, and administrators managing the **His Grace Kitchen LTD** platform at `/admin`.

---

## 1. Access Tiers & Navigation

Log in at `https://your-domain.test/admin` using your assigned staff credentials.

### Staff Roles & Permissions
- **Super Administrator (`super_admin`)**: Full platform control, managing staff accounts, payment gateways, system settings, activity logs, and financial records.
- **Operations Manager (`manager`)**: Full authority over orders, menu pricing, event catering quotes, special requests, and invoice generation.
- **Head Line Chef / Kitchen Staff (`kitchen_staff`)**: Access scoped specifically to the **Orders & Fulfillment** navigation group to view live kitchen tickets, update cooking statuses, and package meals for dispatch.

---

## 2. Order Lifecycle & Kitchen Ticket Operations

Orders received via online checkout automatically appear in **Orders & Fulfillment > Orders**.

### Order Status Progression
1. **Pending Payment (`pending_payment`)**: Customer initialized checkout via Bank Transfer or online payment is processing.
2. **Paid (`paid`)**: Online card payment verified via Paystack or Stripe. Ready for kitchen acknowledgment.
3. **Confirmed (`confirmed`)**: Manager or chef confirmed kitchen capacity and accepted the ticket.
4. **Preparing (`preparing`)**: Line chef is actively cooking the order. Meal items are on the stove/grill.
5. **Ready for Pickup (`ready_for_pickup`)**: For pickup orders, meals are boxed, hot-sealed, and placed in the collection warmer.
6. **Out for Delivery (`out_for_delivery`)**: Dispatch rider has collected the insulated delivery bag with customer delivery slip.
7. **Delivered / Completed (`delivered`)**: Rider confirmed delivery handoff or customer collected pickup.
8. **Cancelled / Refunded (`cancelled`, `refunded`)**: Voided transaction.

### Quick Actions on the Orders Table
The administrative table provides one-click action buttons:
- **Mark Paid**: Use when verifying an offline Bank Transfer or confirming POS payment on delivery. Auto-generates the official PDF invoice.
- **Start Cooking**: Instantly switches status to `preparing`.
- **Dispatch**: Switches status to `out_for_delivery` or `ready_for_pickup`.
- **Complete Order**: Finalizes ticket as `delivered`.
- **Invoice**: Direct download link to the customer's PDF receipt.

---

## 3. Catering Inquiries & Quotation Workflow

Party catering requests from `/catering` arrive in **Events & Special Requests > Catering Requests**.

### Handling an Incoming Catering Inquiry
1. Open the catering request to inspect:
   - Event Date & Venue (e.g., Landmark Event Centre, Lekki)
   - Guest Count (e.g., 150 guests)
   - Selected Package (e.g., *Owambe Grand Wedding & Gala Buffet*)
   - Dietary & Custom Notes (e.g., live suya grill station on terrace, 10 vegan guests).
2. Click **Edit / Send Quote**:
   - **Quoted Total (in minor units / ₦)**: Enter the agreed contract total (e.g. `2,700,000` for ₦2,700,000).
   - **Deposit Required**: Enter the deposit amount required to lock the event calendar date (typically 50%, e.g. `1,350,000`).
   - **Status**: Change to `quote_sent`.
   - **Admin Notes**: Add internal preparation notes or chef instructions.
3. Save the record. The customer automatically receives an email notification with their secure proposal review link: `https://your-domain.test/catering/{reference}`.
4. When the customer accepts and pays their deposit via Paystack or Stripe, the status automatically updates to `deposit_paid` and locks the date.
5. Once the event draws near, the customer or administrator can settle the remaining balance before final dispatch.

---

## 4. Off-Menu Special Requests

Customers seeking traditional heritage delicacies not listed on the main menu submit requests via `/special-request`.

### Sourcing & Quoting a Custom Dish
1. Navigate to **Events & Special Requests > Special Requests**.
2. Review the customer's description (e.g. *5-Litre pot of native Calabar Fisherman Soup with King Prawns and live periwinkles*) and desired date.
3. Consult the kitchen team on current native market ingredients and packaging costs.
4. Update the request:
   - Set **Quoted Total** (e.g. `₦75,000`).
   - Change status to `quote_sent`.
5. The customer receives their payment link at `/special-request/{reference}` to accept and pay immediately.

---

## 5. Invoicing & PDF Generation

Invoices are managed under **Operations & Finance > Invoices**.

### Automatic Invoices
- Every paid order automatically generates an invoice with standard numbering: `INV-2026-XXXXX`.
- The PDF includes company registration info, item breakdown, spice options, delivery fees, and bank account settlement details.
- PDF invoices can be previewed inline (`Stream PDF`) or downloaded directly (`Download PDF`).

### Issuing a Manual Invoice
For corporate accounts, offline catering, or bulk institutional orders:
1. Click **New Invoice**.
2. Select the customer or input their billing details.
3. Build line items with unit prices and quantities.
4. Set invoice due date and terms.
5. Save as `sent` or `paid`. Click **Send Invoice Email** to dispatch the PDF directly to the client's inbox.

---

## 6. Menu & Catalog Management

Managed under **Kitchen & Menu Catalog**:
- **Menu Categories**: Organize food categories (`Rice & Grains`, `Soups & Swallows`, `Grills & Street Food`, `Small Chops & Sides`, `Beverages & Drinks`). Drag or set `sort_order` to control homepage tab position.
- **Menu Items**:
  - `Base Price`: Integer minor units (e.g. `450000` = ₦4,500).
  - `Spice Level`: Mild, Medium, Hot, Extra Hot.
  - `Dietary Tags`: Vegetarian toggle, Popular, Signature, Spicy tags.
  - `Availability Toggle`: Turn off immediately if an ingredient runs out during a shift.
- **Variants**: Configure protein options (e.g., Smoked Chicken, Fried Titus Fish, Assorted Goat Meat, Cow Leg) with price differentials.
- **Add-Ons**: Extras such as Fried Dodo (Plantain), Extra Boiled Egg, Coleslaw, Extra Pepper Sauce.

---

## 7. Delivery Zones & Express Shipping

Managed under **Operations & Finance > Delivery Zones**:
- Local Delivery Zones (e.g., Lekki Phase 1, Victoria Island, Ikeja Mainland) with delivery fees and estimated prep+transit minutes.
- Pay on Delivery Toggle: Enable or disable cash/POS on delivery per zone.
- Diaspora Express Courier Zones:
  - **UK Express Air Freight** (₦45,000 / ~£25) — 48-hour delivery.
  - **North America Express** (₦65,000 / ~$40) — 72-hour air cargo.

---

## 8. Site Settings & WhatsApp Support

Managed under **System & Settings > Site Settings**:
- `whatsapp_number`: Phone number formatted with international code (e.g. `+2348031234567`) for the floating quick-order button on every page.
- `testimonials`: Client feedback quotes, ratings, and dish references.
- `faqs`: Frequently asked questions displayed on the storefront.
- `business_name`, `contact_email`, and `address`: Reflected on the storefront footer and PDF invoices.

