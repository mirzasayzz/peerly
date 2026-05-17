<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="margin: 0; padding: 24px; background: #ffffff; font-family: -apple-system, 'Segoe UI', Roboto, sans-serif; color: #333; font-size: 14px; line-height: 1.7;">

<p style="font-size: 16px; font-weight: 700; margin: 0 0 4px;">Peerly</p>
<p style="color: #888; font-size: 13px; margin: 0 0 24px; padding-bottom: 16px; border-bottom: 1px solid #eee;">Admin Invitation</p>

<p>Hi,</p>
<p>You've been invited to join Peerly as an administrator. You can manage users, posts, and comments on the platform.</p>
<p>Click below to set up your account:</p>

<p style="margin: 24px 0;">
  <a href="{{ $onboardingLink }}" style="background: #3b82f6; color: #ffffff; text-decoration: none; padding: 10px 24px; border-radius: 6px; font-size: 14px; font-weight: 600;">Set Up My Account</a>
</p>

<p style="color: #888; font-size: 12px; margin-top: 24px; padding-top: 16px; border-top: 1px solid #eee;">This link expires in 48 hours. If you didn't expect this, ignore it.</p>
<p style="color: #aaa; font-size: 11px;">{{ $onboardingLink }}</p>

</body>
</html>
