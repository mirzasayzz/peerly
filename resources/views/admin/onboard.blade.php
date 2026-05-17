<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Complete Your Peerly Admin Setup</title>
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
    @vite(['resources/css/app.css'])
    <style>
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: var(--bg-primary); padding: 24px; }
        .onboard-card { width: 100%; max-width: 460px; background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 20px; overflow: hidden; }
        .onboard-header { background: linear-gradient(135deg, #7c5cfc, #00d4ff); padding: 36px 32px; text-align: center; }
        .onboard-header h1 { color: white; font-size: 24px; font-weight: 800; margin: 0 0 6px; }
        .onboard-header p { color: rgba(255,255,255,0.85); margin: 0; font-size: 14px; }
        .onboard-body { padding: 32px; }
        .steps { display: flex; align-items: center; gap: 8px; margin-bottom: 28px; }
        .step { flex: 1; height: 4px; border-radius: 2px; background: var(--border); }
        .step.done { background: var(--accent); }
        .form-label { font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px; display: block; }
        .form-field { margin-bottom: 20px; }
        .form-hint { font-size: 12px; color: var(--text-tertiary); margin-top: 5px; }
        .email-badge { display: flex; align-items: center; gap: 10px; padding: 12px 14px; background: var(--bg-tertiary); border: 1px solid var(--border); border-radius: var(--radius-md); margin-bottom: 24px; }
        .email-badge span { font-size: 14px; font-weight: 600; color: var(--text-primary); }
        .email-badge i { color: var(--accent); font-size: 18px; }
    </style>
</head>
<body>
<div class="onboard-card">
    <div class="onboard-header">
        <div style="font-size: 36px; margin-bottom: 10px;">🛡️</div>
        <h1>Complete Your Admin Setup</h1>
        <p>You've been invited to join Peerly as an administrator</p>
    </div>
    <div class="onboard-body">
        <div class="steps">
            <div class="step done"></div>
            <div class="step done"></div>
            <div class="step"></div>
        </div>

        <p style="font-size: 13px; color: var(--text-tertiary); margin-bottom: 20px;">
            Setting up account for:
        </p>
        <div class="email-badge">
            <i class="ph ph-envelope-simple"></i>
            <span>{{ $invitation->email }}</span>
        </div>

        @if($errors->any())
            <div style="padding: 12px; background: var(--danger-soft); color: var(--danger); border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.onboard.complete', $token) }}">
            @csrf
            <div class="form-field">
                <label class="form-label" for="name">Your Full Name</label>
                <input type="text" id="name" name="name" class="form-input" placeholder="e.g. Ahmed Khan"
                       value="{{ old('name') }}" required autofocus
                       style="width:100%; height:44px; padding: 0 14px; background: var(--bg-input); border: 1px solid var(--border); border-radius: var(--radius-md); color: var(--text-primary); font-size: 14px; box-sizing:border-box;">
                <p class="form-hint">This will be your display name on the platform</p>
            </div>

            <div class="form-field">
                <label class="form-label" for="password">Set a Password</label>
                <input type="password" id="password" name="password" class="form-input" placeholder="At least 8 characters"
                       required
                       style="width:100%; height:44px; padding: 0 14px; background: var(--bg-input); border: 1px solid var(--border); border-radius: var(--radius-md); color: var(--text-primary); font-size: 14px; box-sizing:border-box;">
                <p class="form-hint">Minimum 8 characters. Choose something strong!</p>
            </div>

            <div class="form-field">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                       placeholder="Repeat your password" required
                       style="width:100%; height:44px; padding: 0 14px; background: var(--bg-input); border: 1px solid var(--border); border-radius: var(--radius-md); color: var(--text-primary); font-size: 14px; box-sizing:border-box;">
            </div>

            <button type="submit" class="btn btn-primary w-full" style="height: 48px; font-size: 15px; font-weight: 700; margin-top: 8px;">
                <i class="ph ph-check-circle"></i> Activate My Admin Account
            </button>
        </form>

        <p style="text-align: center; font-size: 12px; color: var(--text-tertiary); margin-top: 20px;">
            Once activated, you'll receive a confirmation email and be redirected to log in.
        </p>
    </div>
</div>
</body>
</html>
