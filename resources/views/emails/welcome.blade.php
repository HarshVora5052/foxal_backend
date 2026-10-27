<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to Foxal</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #f7f7f9; margin: 0; padding: 40px 20px; color: #222; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 36px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
        .header { text-align: center; border-bottom: 1px solid #eee; padding-bottom: 24px; }
        .brand { font-size: 28px; font-weight: 700; letter-spacing: 3px; color: #111; margin: 0; }
        .content { padding: 24px 0; line-height: 1.6; }
        .button { display: inline-block; background: #111; color: #fff !important; text-decoration: none; padding: 12px 30px; border-radius: 6px; font-weight: 600; margin-top: 16px; }
        .footer { text-align: center; font-size: 12px; color: #888; border-top: 1px solid #eee; padding-top: 20px; margin-top: 24px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="brand">FOXAL</h1>
            <p style="margin: 4px 0 0; color: #666; font-size: 13px; letter-spacing: 1px;">HAUTE PARFUMERIE</p>
        </div>
        <div class="content">
            <h2>Welcome, {{ $user->name }}!</h2>
            <p>Thank you for creating an account with Foxal. We craft exceptional fragrances designed to leave an unforgettable impression.</p>
            <p>You can now explore our curated collections, save your favorite scents to your wishlist, and track your orders in real time.</p>
            <p style="text-align: center;">
                <a href="{{ config('app.url') }}" class="button">Explore Collections</a>
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Foxal. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
