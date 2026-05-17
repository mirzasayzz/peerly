<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  body { margin: 0; padding: 0; background: #0f0f13; font-family: 'Segoe UI', sans-serif; }
  .wrapper { max-width: 560px; margin: 40px auto; background: #1a1a2e; border-radius: 16px; overflow: hidden; border: 1px solid #2a2a3e; }
  .header { background: linear-gradient(135deg, #7c5cfc, #00d4ff); padding: 40px 32px; text-align: center; }
  .header h1 { color: white; font-size: 28px; margin: 0 0 8px; font-weight: 800; }
  .header p { color: rgba(255,255,255,0.85); margin: 0; font-size: 15px; }
  .body { padding: 36px 32px; }
  .badge { display: inline-block; background: rgba(124,92,252,0.15); border: 1px solid rgba(124,92,252,0.4); color: #7c5cfc; padding: 6px 16px; border-radius: 999px; font-size: 13px; font-weight: 600; margin-bottom: 24px; }
  .body p { color: #a0a0c0; font-size: 15px; line-height: 1.7; margin: 0 0 20px; }
  .btn { display: block; width: fit-content; margin: 28px auto; background: linear-gradient(135deg, #7c5cfc, #00d4ff); color: white; text-decoration: none; padding: 14px 36px; border-radius: 10px; font-size: 16px; font-weight: 700; text-align: center; }
  .note { background: rgba(255,255,255,0.04); border-left: 3px solid #7c5cfc; padding: 14px 18px; border-radius: 0 8px 8px 0; margin: 24px 0; }
  .note p { color: #7a7a9a; font-size: 13px; margin: 0; }
  .footer { padding: 24px 32px; border-top: 1px solid #2a2a3e; text-align: center; }
  .footer p { color: #4a4a6a; font-size: 12px; margin: 0; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h1>🛡️ You're Invited!</h1>
    <p>You've been selected as an Administrator on Peerly</p>
  </div>
  <div class="body">
    <div style="text-align:center">
      <span class="badge">⭐ Admin Invitation</span>
    </div>
    <p>Hi there,</p>
    <p>The <strong style="color:#e0e0ff">Peerly Super Admin</strong> has personally invited you to join as an <strong style="color:#7c5cfc">Administrator</strong> on the Peerly Student Community platform.</p>
    <p>As an admin, you'll have the ability to manage users, posts, and comments to keep the community safe and thriving.</p>
    <p>To get started, click the button below to complete your onboarding — you'll set your name and password, and then your admin account will be activated instantly.</p>
    <a href="{{ $onboardingLink }}" class="btn">Complete My Onboarding →</a>
    <div class="note">
      <p>⏰ This invitation link expires in <strong>48 hours</strong>. If you did not expect this email, you can safely ignore it.</p>
    </div>
    <p>We're excited to have you on the team!</p>
    <p style="color:#7c5cfc; font-weight:700;">— The Peerly Team</p>
  </div>
  <div class="footer">
    <p>If the button doesn't work, copy and paste this link:<br>
    <span style="color:#7c5cfc; word-break:break-all;">{{ $onboardingLink }}</span></p>
  </div>
</div>
</body>
</html>
