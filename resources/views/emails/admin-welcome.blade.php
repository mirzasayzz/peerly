<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  body { margin: 0; padding: 0; background: #0f0f13; font-family: 'Segoe UI', sans-serif; }
  .wrapper { max-width: 560px; margin: 40px auto; background: #1a1a2e; border-radius: 16px; overflow: hidden; border: 1px solid #2a2a3e; }
  .header { background: linear-gradient(135deg, #4ade80, #22d3ee); padding: 40px 32px; text-align: center; }
  .header h1 { color: white; font-size: 28px; margin: 0 0 8px; font-weight: 800; }
  .header p { color: rgba(255,255,255,0.85); margin: 0; font-size: 15px; }
  .body { padding: 36px 32px; }
  .badge { display: inline-block; background: rgba(74,222,128,0.15); border: 1px solid rgba(74,222,128,0.4); color: #4ade80; padding: 6px 16px; border-radius: 999px; font-size: 13px; font-weight: 600; margin-bottom: 24px; }
  .body p { color: #a0a0c0; font-size: 15px; line-height: 1.7; margin: 0 0 20px; }
  .perks { background: rgba(255,255,255,0.04); border-radius: 10px; padding: 20px; margin: 20px 0; }
  .perk { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; color: #c0c0e0; font-size: 14px; }
  .perk:last-child { margin-bottom: 0; }
  .btn { display: block; width: fit-content; margin: 28px auto; background: linear-gradient(135deg, #4ade80, #22d3ee); color: white; text-decoration: none; padding: 14px 36px; border-radius: 10px; font-size: 16px; font-weight: 700; text-align: center; }
  .footer { padding: 24px 32px; border-top: 1px solid #2a2a3e; text-align: center; }
  .footer p { color: #4a4a6a; font-size: 12px; margin: 0; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h1>🎉 Welcome, Admin!</h1>
    <p>Your Peerly Admin account is now active</p>
  </div>
  <div class="body">
    <div style="text-align:center">
      <span class="badge">✅ Account Activated</span>
    </div>
    <p>Hi <strong style="color:#e0e0ff">{{ $adminName }}</strong>,</p>
    <p>Congratulations! Your admin onboarding is complete. You now have full administrator access to the <strong style="color:#4ade80">Peerly</strong> platform.</p>
    <div class="perks">
      <div class="perk">🛡️ <span>Manage and moderate community posts</span></div>
      <div class="perk">👥 <span>View and manage all user accounts</span></div>
      <div class="perk">💬 <span>Delete inappropriate comments</span></div>
      <div class="perk">📌 <span>Pin important discussions</span></div>
    </div>
    <p>You can access the admin panel anytime by visiting the link below. Log in with the email and password you just set.</p>
    <a href="{{ url('/admin') }}" class="btn">Go to Admin Panel →</a>
    <p>If you ever need help or have questions, reach out to the Super Admin at tubamirza822@gmail.com.</p>
    <p style="color:#4ade80; font-weight:700;">— The Peerly Team</p>
  </div>
  <div class="footer">
    <p>© {{ date('Y') }} Peerly — Student Community Platform</p>
  </div>
</div>
</body>
</html>
