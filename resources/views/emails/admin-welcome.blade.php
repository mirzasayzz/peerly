<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        h1 { color: #0f172a; font-size: 24px; margin-bottom: 16px; }
        p { color: #475569; font-size: 16px; line-height: 1.6; margin-bottom: 24px; }
        .btn { display: inline-block; background: #7c5cfc; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Peerly Administration</h1>
        <p>You have been invited by the Super Admin to join the Peerly team as an Administrator!</p>
        <p>To get started, please click the button below to securely set your password and personalize your profile details (like your name and username).</p>
        <p style="text-align: center;">
            <a href="{{ $resetLink }}" class="btn">Set My Password & Join</a>
        </p>
        <p>If you have any questions, please contact the Super Admin.</p>
        <p>Best,<br>The Peerly Team</p>
    </div>
</body>
</html>
