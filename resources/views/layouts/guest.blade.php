<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Peerly — Sign In' }}</title>
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .auth-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; background: var(--bg-primary); }
        .auth-card { width: 100%; max-width: 420px; background: var(--bg-secondary); border: 1px solid var(--border); border-radius: var(--radius-xl); padding: 40px; box-shadow: var(--shadow-lg); }
        .auth-logo { display: flex; align-items: center; justify-content: center; gap: 10px; font-size: 24px; font-weight: 700; margin-bottom: 8px; }
        .auth-logo .logo-icon { width: 40px; height: 40px; background: var(--gradient); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 20px; }
        .auth-subtitle { text-align: center; color: var(--text-tertiary); font-size: 14px; margin-bottom: 32px; }
        .auth-footer { text-align: center; margin-top: 24px; font-size: 14px; color: var(--text-tertiary); }
        .auth-footer a { color: var(--accent); font-weight: 500; }
        .auth-footer a:hover { text-decoration: underline; }
        .auth-divider { display: flex; align-items: center; gap: 12px; margin: 24px 0; color: var(--text-tertiary); font-size: 13px; }
        .auth-divider::before, .auth-divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }
    </style>
</head>
<body>
    <div class="auth-page">
        <div class="auth-card fade-in">
            <div class="auth-logo">
                <span class="logo-icon">P</span>
                Peerly
            </div>
            {{ $slot }}
        </div>
    </div>
</body>
</html>
