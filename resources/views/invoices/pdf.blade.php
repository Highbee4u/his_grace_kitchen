<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
        body {
            font-size: 12px;
            color: #292524;
            background: #ffffff;
            padding: 35px;
            line-height: 1.5;
        }
        .header {
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 3px solid #0D4A2B;
            padding-bottom: 20px;
        }
        .header table {
            width: 100%;
        }
        .brand-title {
            font-size: 24px;
            font-weight: bold;
            color: #0D4A2B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .brand-subtitle {
            font-size: 10px;
            color: #D97706;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .brand-address {
            font-size: 10px;
            color: #78716c;
            margin-top: 5px;
            line-height: 1.4;
        }
        .invoice-title {
            font-size: 26px;
            font-weight: bold;
            color: #1c1917;
            text-align: right;
        }
        .invoice-meta {
            font-size: 11px;
            color: #57534e;
            text-align: right;
            margin-top: 4px;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 4px;
            margin-top: 4px;
        }
        .badge-paid {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-sent, .badge-draft {
            background-color: #fef3c7;
            color: #92400e;
        }
        .client-info {
            width: 100%;
            margin-bottom: 25px;
        }
        .client-info table {
            width: 100%;
        }
        .section-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #78716c;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .client-name {
            font-size: 13px;
            font-weight: bold;
            color: #1c1917;
        }
        .client-detail {
            font-size: 11px;
            color: #57534e;
            line-height: 1.4;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .items-table th {
            background-color: #0D4A2B;
            color: #ffffff;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 10px;
            text-align: left;
        }
        .items-table th.text-right {
            text-align: right;
        }
        .items-table th.text-center {
            text-align: center;
        }
        .items-table td {
            padding: 9px 10px;
            border-bottom: 1px solid #e7e5e4;
            font-size: 11px;
        }
        .items-table tr:nth-child(even) td {
            background-color: #fafaf9;
        }
        .totals-table {
            width: 100%;
            margin-bottom: 30px;
        }
        .totals-table table {
            width: 45%;
            margin-left: auto;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 5px 8px;
            font-size: 11px;
            color: #57534e;
        }
        .totals-table tr.grand-total td {
            border-top: 2px solid #0D4A2B;
            border-bottom: 2px solid #0D4A2B;
            font-size: 14px;
            font-weight: bold;
            color: #0D4A2B;
            padding: 8px 8px;
        }
        .payment-info {
            background-color: #fafaf9;
            border: 1px solid #e7e5e4;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 25px;
            font-size: 10px;
            color: #57534e;
        }
        .payment-info strong {
            color: #1c1917;
        }
        .footer {
            border-top: 1px solid #e7e5e4;
            padding-top: 15px;
            text-align: center;
            font-size: 10px;
            color: #a8a29e;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <table>
            <tr>
                <td style="width: 55%; vertical-align: top;">
                    <div class="brand-title">Nigerian Kitchen</div>
                    <div class="brand-subtitle">Authentic Native Gastronomy & Catering</div>
                    <div class="brand-address">
                        Admiralty Way, Lekki Phase 1, Lagos, Nigeria<br>
                        Email: hello@example.com • WhatsApp Orders: +234 800 000 0000<br>
                        RC: 1892044 • www.nigeriankitchen.test
                    </div>
                </td>
                <td style="width: 45%; vertical-align: top;">
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-meta">
                        <strong>Invoice Number:</strong> {{ $invoice->number }}<br>
                        <strong>Issue Date:</strong> {{ $invoice->created_at->format('M d, Y') }}<br>
                        <strong>Due Date:</strong> {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') : $invoice->created_at->addDays(7)->format('M d, Y') }}<br>
                        @if($invoice->order)
                            <strong>Order Reference:</strong> #{{ $invoice->order->order_number }}<br>
                        @endif
                        <span class="badge {{ $invoice->status === 'paid' ? 'badge-paid' : 'badge-sent' }}">
                            {{ strtoupper($invoice->status) }}
                        </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Client / Bill To -->
    <div class="client-info">
        <table>
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <div class="section-label">Billed To:</div>
                    <div class="client-name">
                        {{ $invoice->order?->customer_name ?? $invoice->user?->name ?? 'Valued Customer' }}
                    </div>
                    <div class="client-detail">
                        Email: {{ $invoice->order?->customer_email ?? $invoice->user?->email ?? 'customer@example.com' }}<br>
                        Phone: {{ $invoice->order?->customer_phone ?? 'N/A' }}
                    </div>
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <div class="section-label">Fulfillment / Delivery Address:</div>
                    <div class="client-detail">
                        @if($invoice->order && $invoice->order->fulfilment_type === 'delivery')
                            {{ $invoice->order->delivery_address['street'] ?? 'Street Address' }}<br>
                            {{ $invoice->order->delivery_address['city'] ?? '' }}, {{ $invoice->order->delivery_address['state'] ?? 'Nigeria' }}<br>
                            Zone: {{ $invoice->order->deliveryZone?->name ?? 'Standard Zone' }}
                        @else
                            Kitchen Pickup Counter<br>
                            Admiralty Way, Lekki Phase 1, Lagos
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Line Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 45%;">Item Description</th>
                <th class="text-center" style="width: 15%;">Quantity</th>
                <th class="text-right" style="width: 20%;">Unit Price</th>
                <th class="text-right" style="width: 20%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($invoice->line_items) && is_array($invoice->line_items))
                @foreach($invoice->line_items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item['name'] ?? 'Dish' }}</strong>
                            @if(!empty($item['variant']))
                                <br><span style="font-size: 9px; color: #b45309;">{{ $item['variant'] }}</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $item['quantity'] ?? 1 }}</td>
                        <td class="text-right">{{ \App\Support\Money::format($item['unit_price_minor'] ?? 0, $invoice->currency) }}</td>
                        <td class="text-right" style="font-weight: bold;">
                            {{ \App\Support\Money::format($item['total_minor'] ?? (($item['unit_price_minor'] ?? 0) * ($item['quantity'] ?? 1)), $invoice->currency) }}
                        </td>
                    </tr>
                @endforeach
            @elseif($invoice->order)
                @foreach($invoice->order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->name }}</strong>
                            @if(!empty($item->options['variant']))
                                <br><span style="font-size: 9px; color: #b45309;">{{ $item->options['variant'] }}</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">{{ \App\Support\Money::format($item->unit_price_minor, $invoice->currency) }}</td>
                        <td class="text-right" style="font-weight: bold;">{{ \App\Support\Money::format($item->total_minor, $invoice->currency) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" style="text-align: center; color: #a8a29e;">Catering & Culinary Services</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Totals Table -->
    <div class="totals-table">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td style="text-align: right; font-weight: bold;">{{ \App\Support\Money::format($invoice->subtotal_minor, $invoice->currency) }}</td>
            </tr>
            @if($invoice->delivery_fee_minor > 0)
                <tr>
                    <td>Delivery Fee:</td>
                    <td style="text-align: right;">{{ \App\Support\Money::format($invoice->delivery_fee_minor, $invoice->currency) }}</td>
                </tr>
            @endif
            @if($invoice->discount_minor > 0)
                <tr>
                    <td>Discount:</td>
                    <td style="text-align: right; color: #dc2626;">-{{ \App\Support\Money::format($invoice->discount_minor, $invoice->currency) }}</td>
                </tr>
            @endif
            @if($invoice->tax_minor > 0)
                <tr>
                    <td>Tax / VAT:</td>
                    <td style="text-align: right;">{{ \App\Support\Money::format($invoice->tax_minor, $invoice->currency) }}</td>
                </tr>
            @endif
            <tr class="grand-total">
                <td>Total Amount:</td>
                <td style="text-align: right;">{{ \App\Support\Money::format($invoice->total_minor, $invoice->currency) }}</td>
            </tr>
        </table>
    </div>

    <!-- Official Bank Details -->
    <div class="payment-info">
        <strong>Official Settlement Bank Details:</strong><br>
        Guaranty Trust Bank (GTBank): Account No: <strong>0123456789</strong> • Name: Nigerian Kitchen Hospitality Ltd<br>
        Zenith Bank: Account No: <strong>1012345678</strong> • Name: Nigerian Kitchen Hospitality Ltd<br>
        Payment Reference: <strong>{{ $invoice->number }}</strong>
    </div>

    <!-- Notes -->
    @if($invoice->notes)
        <div style="font-size: 10px; color: #78716c; margin-bottom: 20px;">
            <strong>Notes & Inclusions:</strong> {{ $invoice->notes }}
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        Thank you for choosing Nigerian Kitchen. Fresh ingredients. Uncompromising tradition.<br>
        This document serves as an official electronic receipt and tax invoice.
    </div>

</body>
</html>

