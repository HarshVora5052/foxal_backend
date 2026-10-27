<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; line-height: 1.4; margin: 0; padding: 20px; }
        .header { border-bottom: 2px solid #111; padding-bottom: 15px; margin-bottom: 25px; }
        .logo { font-size: 26px; font-weight: bold; letter-spacing: 3px; color: #111; margin: 0; }
        .subtitle { font-size: 10px; color: #666; letter-spacing: 1px; margin: 2px 0 0; }
        .invoice-title { float: right; text-align: right; margin-top: -40px; }
        .invoice-title h2 { margin: 0; font-size: 20px; color: #111; }
        .info-section { width: 100%; margin-bottom: 25px; }
        .info-col { width: 48%; display: inline-block; vertical-align: top; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th { background: #f2f2f2; border-bottom: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; text-transform: uppercase; }
        .table td { border-bottom: 1px solid #eee; padding: 8px; font-size: 11px; }
        .totals-table { width: 40%; float: right; margin-top: 20px; border-collapse: collapse; }
        .totals-table td { padding: 6px 8px; font-size: 11px; }
        .totals-table .grand-total { font-size: 13px; font-weight: bold; border-top: 2px solid #111; border-bottom: 2px solid #111; }
        .footer { margin-top: 140px; text-align: center; font-size: 10px; color: #888; border-top: 1px solid #eee; padding-top: 10px; }
        .badge { display: inline-block; padding: 3px 8px; font-size: 10px; border-radius: 4px; font-weight: bold; background: #eee; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="logo">FOXAL</h1>
        <p class="subtitle">HAUTE PARFUMERIE</p>
        <div class="invoice-title">
            <h2>TAX INVOICE</h2>
            <p style="margin: 3px 0;"><strong>Invoice #:</strong> {{ $order->order_number }}</p>
            <p style="margin: 3px 0;"><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>
            <p style="margin: 3px 0;"><strong>Status:</strong> <span class="badge">{{ $order->status }}</span></p>
        </div>
    </div>

    <div class="info-section">
        <div class="info-col">
            <strong>Billed & Shipped To:</strong><br>
            @php $addr = $order->address_snapshot; @endphp
            @if(is_array($addr))
                {{ $addr['full_name'] ?? '' }}<br>
                {{ $addr['line1'] ?? '' }}<br>
                @if(!empty($addr['line2'])) {{ $addr['line2'] }}<br> @endif
                {{ $addr['city'] ?? '' }}, {{ $addr['state'] ?? '' }} - {{ $addr['pincode'] ?? '' }}<br>
                Phone: {{ $addr['phone'] ?? '' }}
            @else
                {{ $order->user?->name }}<br>
                {{ $order->user?->email }}
            @endif
        </div>
        <div class="info-col" style="text-align: right;">
            <strong>Sold By:</strong><br>
            Foxal Luxury Goods Pvt. Ltd.<br>
            Level 5, Signature Tower<br>
            Mumbai, Maharashtra 400001<br>
            GSTIN: 27AABCF1234F1Z5<br>
            Email: concierge@foxal.com
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 50%;">Item & Description</th>
                <th style="width: 15%; text-align: right;">Unit Price</th>
                <th style="width: 10%; text-align: center;">Qty</th>
                <th style="width: 20%; text-align: right;">Amount (INR)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                        @if($item->product_sku)
                            <br><span style="color: #777; font-size: 10px;">SKU: {{ $item->product_sku }}</span>
                        @endif
                    </td>
                    <td style="text-align: right;">₹{{ number_format($item->price, 2) }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">₹{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td>Subtotal:</td>
            <td style="text-align: right;">₹{{ number_format($order->subtotal, 2) }}</td>
        </tr>
        @if($order->discount > 0)
            <tr>
                <td style="color: #16a34a;">Discount:</td>
                <td style="text-align: right; color: #16a34a;">-₹{{ number_format($order->discount, 2) }}</td>
            </tr>
        @endif
        <tr>
            <td>Shipping:</td>
            <td style="text-align: right;">{{ $order->shipping == 0 ? 'FREE' : '₹' . number_format($order->shipping, 2) }}</td>
        </tr>
        @if($order->gst_amount > 0)
            <tr>
                <td>GST (18% included):</td>
                <td style="text-align: right;">₹{{ number_format($order->gst_amount, 2) }}</td>
            </tr>
        @endif
        <tr class="grand-total">
            <td>Grand Total:</td>
            <td style="text-align: right;">₹{{ number_format($order->total, 2) }}</td>
        </tr>
        <tr>
            <td colspan="2" style="font-size: 10px; color: #666; padding-top: 8px;">
                Payment Method: {{ strtoupper($order->payment_method) }} ({{ $order->payment_status }})
            </td>
        </tr>
    </table>

    <div style="clear: both;"></div>

    <div class="footer">
        <p>This is a computer-generated invoice and requires no physical signature.</p>
        <p>Thank you for choosing Foxal. For questions, contact concierge@foxal.com.</p>
    </div>
</body>
</html>
