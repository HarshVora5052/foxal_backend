<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmation #{{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #f7f7f9; margin: 0; padding: 40px 20px; color: #222; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 36px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
        .header { text-align: center; border-bottom: 1px solid #eee; padding-bottom: 24px; }
        .brand { font-size: 28px; font-weight: 700; letter-spacing: 3px; color: #111; margin: 0; }
        .table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; }
        .table th { background: #fafafa; font-weight: 600; }
        .total-row td { font-weight: 700; font-size: 16px; border-top: 2px solid #111; }
        .footer { text-align: center; font-size: 12px; color: #888; border-top: 1px solid #eee; padding-top: 20px; margin-top: 24px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="brand">FOXAL</h1>
            <p style="margin: 4px 0 0; color: #666; font-size: 13px; letter-spacing: 1px;">HAUTE PARFUMERIE</p>
        </div>
        <div style="padding: 20px 0;">
            <h2>Thank You for Your Order!</h2>
            <p>Your order <strong>#{{ $order->order_number }}</strong> has been received and is being processed.</p>
            <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }} ({{ $order->payment_status }})</p>

            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ number_format($item->price, 2) }}</td>
                            <td>₹{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" style="text-align: right;">Subtotal:</td>
                        <td>₹{{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    @if($order->discount > 0)
                        <tr>
                            <td colspan="3" style="text-align: right; color: #16a34a;">Discount:</td>
                            <td style="color: #16a34a;">-₹{{ number_format($order->discount, 2) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="3" style="text-align: right;">Shipping:</td>
                        <td>{{ $order->shipping == 0 ? 'FREE' : '₹' . number_format($order->shipping, 2) }}</td>
                    </tr>
                    @if($order->gst_amount > 0)
                        <tr>
                            <td colspan="3" style="text-align: right;">GST (Included):</td>
                            <td>₹{{ number_format($order->gst_amount, 2) }}</td>
                        </tr>
                    @endif
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right;">Total:</td>
                        <td>₹{{ number_format($order->total, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Foxal. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
