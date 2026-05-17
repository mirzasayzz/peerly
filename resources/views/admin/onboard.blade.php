<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Setup — Peerly</title>
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background: var(--bg-primary); min-height: 100vh;">
    <div style="max-width: 560px; margin: 0 auto; padding: 40px 20px;">

        {{-- Simple header --}}
        <div style="margin-bottom: 32px;">
            <a href="{{ url('/') }}" style="display: inline-flex; align-items: center; gap: 8px; font-size: 18px; font-weight: 700; color: var(--text-primary); margin-bottom: 24px;">
                <img src="{{ asset('images/peerly.png') }}" alt="Peerly" style="height: 28px; width: auto;">
                Peerly
            </a>
            <h1 style="font-size: 22px; font-weight: 700; margin-bottom: 6px;">Admin Account Setup</h1>
            <p style="color: var(--text-tertiary); font-size: 14px;">Complete your invite to become an administrator</p>
        </div>

        {{-- Invited email display --}}
        <div style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: var(--bg-tertiary); border: 1px solid var(--border); border-radius: var(--radius-md); margin-bottom: 24px;">
            <i class="ph ph-envelope-simple" style="color: var(--accent); font-size: 16px; flex-shrink:0;"></i>
            <span style="font-size: 14px; font-weight: 600; color: var(--text-primary);">{{ $invitation->email }}</span>
        </div>

        @if($errors->any())
            <div style="padding: 12px; background: var(--danger-soft); color: var(--danger); border-radius: 8px; margin-bottom: 16px; font-size: 13px;">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.onboard.complete', $token) }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-input"
                       placeholder="Your name" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input"
                       placeholder="At least 8 characters" required autocomplete="new-password">
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                       placeholder="Repeat your password" required autocomplete="new-password">
                <p id="password-match-msg" style="font-size: 12px; margin-top: 5px; display: none;"></p>
            </div>

            <button type="submit" id="submit-btn" class="btn btn-primary" style="width: 100%; height: 44px; font-size: 15px; margin-top: 8px;">
                Activate My Admin Account
            </button>
        </form>

        <p style="text-align: center; margin-top: 16px; font-size: 13px; color: var(--text-tertiary);">
            Already have an account? <a href="{{ route('login') }}" style="color: var(--accent); font-weight: 500;">Sign in</a>
        </p>
    </div>

    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        const matchMsg = document.getElementById('password-match-msg');
        const submitBtn = document.getElementById('submit-btn');

        function checkPasswords() {
            if (confirmPassword.value.length === 0) {
                matchMsg.style.display = 'none';
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                return;
            }
            matchMsg.style.display = 'block';
            if (password.value === confirmPassword.value) {
                matchMsg.textContent = '✓ Passwords match';
                matchMsg.style.color = '#4ade80';
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
            } else {
                matchMsg.textContent = '✗ Passwords do not match';
                matchMsg.style.color = '#ef4444';
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
            }
        }

        password.addEventListener('input', checkPasswords);
        confirmPassword.addEventListener('input', checkPasswords);
    </script>
</body>
</html>
