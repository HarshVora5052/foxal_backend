<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Status Update #{{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #f7f7f9; margin: 0; padding: 40px 20px; color: #222; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 36px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
        .header { text-align: center; border-bottom: 1px solid #eee; padding-bottom: 24px; }
        .brand { font-size: 28px; font-weight: 700; letter-spacing: 3px; color: #111; margin: 0; }
        .status-badge { display: inline-block; padding: 8px 18px; border-radius: 20px; font-weight: 700; font-size: 14px; background: #111; color: #fff; margin: 12px 0; }
        .footer { text-align: center; font-size: 12px; color: #888; border-top: 1px solid #eee; padding-top: 20px; margin-top: 24px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="brand">FOXAL</h1>
            <p style="margin: 4px 0 0; color: #666; font-size: 13px; letter-spacing: 1px;">HAUTE PARFUMERIE</p>
        </div>
        <div style="padding: 24px 0; line-height: 1.6;">
            <h2>Order Status Update</h2>
            <p>Your order <strong>#{{ $order->order_number }}</strong> has been updated.</p>
            <div style="text-align: center;">
                <span class="status-badge">{{ $order->status }}</span>
            </div>

            @if($order->tracking_number)
                <p><strong>Tracking Number:</strong> {{ $order->tracking_number }}</p>
            @endif

            <p>You can view full details by logging into your account.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Foxal. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
