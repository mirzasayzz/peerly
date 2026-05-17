<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="margin: 0; padding: 24px; background: #ffffff; font-family: -apple-system, 'Segoe UI', Roboto, sans-serif; color: #333; font-size: 14px; line-height: 1.7;">

<p style="font-size: 16px; font-weight: 700; margin: 0 0 4px;">Peerly</p>
<p style="color: #888; font-size: 13px; margin: 0 0 24px; padding-bottom: 16px; border-bottom: 1px solid #eee;">Account Activated</p>

<p>Hi {{ $adminName }},</p>
<p>Your admin account on Peerly is now active. You can manage users, posts, and comments from the admin panel.</p>
<p>Log in with the email and password you just set up:</p>

<p style="margin: 24px 0;">
  <a href="{{ url('/admin') }}" style="background: #3b82f6; color: #ffffff; text-decoration: none; padding: 10px 24px; border-radius: 6px; font-size: 14px; font-weight: 600;">Go to Admin Panel</a>
</p>

<p style="color: #888; font-size: 12px; margin-top: 24px; padding-top: 16px; border-top: 1px solid #eee;">&copy; {{ date('Y') }} Peerly</p>

</body>
</html>
