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
  .note { background: #fafafa; border: 1px solid #eee; padding: 12px 16px; border-radius: 6px; margin: 20px 0 0; }
  .note p { color: #888; font-size: 12px; margin: 0; }
  .footer { padding: 20px 28px; border-top: 1px solid #eee; }
  .footer p { color: #999; font-size: 11px; margin: 0; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h1>Admin Invitation</h1>
    <p>Peerly Student Community</p>
  </div>
  <div class="body">
    <p>Hi,</p>
    <p>You've been invited to join <strong>Peerly</strong> as an administrator. As an admin, you can manage users, posts, and comments on the platform.</p>
    <p>Click the button below to set up your account:</p>
    <p style="text-align: center; margin: 24px 0;">
      <a href="{{ $onboardingLink }}" class="btn">Set Up My Account</a>
    </p>
    <div class="note">
      <p>This link expires in 48 hours. If you didn't expect this email, you can ignore it.</p>
    </div>
  </div>
  <div class="footer">
    <p>If the button doesn't work, copy this link: {{ $onboardingLink }}</p>
  </div>
</div>
</body>
</html>
