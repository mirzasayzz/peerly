<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  body { margin: 0; padding: 0; background: #f5f5f5; font-family: -apple-system, 'Segoe UI', Roboto, sans-serif; }
  .wrapper { max-width: 520px; margin: 32px auto; background: #ffffff; border-radius: 8px; border: 1px solid #e5e5e5; }
  .header { padding: 28px 28px 0; }
  .header h1 { font-size: 20px; font-weight: 700; color: #111; margin: 0 0 4px; }
  .header p { font-size: 13px; color: #666; margin: 0; }
  .body { padding: 24px 28px; }
  .body p { color: #333; font-size: 14px; line-height: 1.7; margin: 0 0 16px; }
  .btn { display: inline-block; background: #3b82f6; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 6px; font-size: 14px; font-weight: 600; }
  .footer { padding: 20px 28px; border-top: 1px solid #eee; }
  .footer p { color: #999; font-size: 11px; margin: 0; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h1>Welcome to the team</h1>
    <p>Peerly Student Community</p>
  </div>
  <div class="body">
    <p>Hi {{ $adminName }},</p>
    <p>Your admin account on <strong>Peerly</strong> is now active. You can manage users, posts, and comments from the admin panel.</p>
    <p>Log in with the email and password you just set up:</p>
    <p style="text-align: center; margin: 24px 0;">
      <a href="{{ url('/admin') }}" class="btn">Go to Admin Panel</a>
    </p>
    <p style="color: #888; font-size: 13px;">If you have any questions, contact the Super Admin.</p>
  </div>
  <div class="footer">
    <p>&copy; {{ date('Y') }} Peerly</p>
  </div>
</div>
</body>
</html>
