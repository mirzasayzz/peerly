<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Peerly — Sign In' }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <meta name="theme-color" content="#3b82f6">

    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .auth-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 16px; background: var(--bg-primary); }
        .auth-card { width: 100%; max-width: 420px; background: var(--bg-secondary); border: 1px solid var(--border); border-radius: var(--radius-xl); padding: 40px; box-shadow: var(--shadow-lg); }
        .auth-logo { display: flex; align-items: center; justify-content: center; gap: 10px; font-size: 24px; font-weight: 700; margin-bottom: 8px; }
        .auth-logo .logo-icon { width: 40px; height: 40px; background: var(--gradient); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 20px; }
        .auth-subtitle { text-align: center; color: var(--text-tertiary); font-size: 14px; margin-bottom: 32px; }
        .auth-footer { text-align: center; margin-top: 24px; font-size: 14px; color: var(--text-tertiary); }
        .auth-footer a { color: var(--accent); font-weight: 500; }
        .auth-footer a:hover { text-decoration: underline; }
        .auth-divider { display: flex; align-items: center; gap: 12px; margin: 24px 0; color: var(--text-tertiary); font-size: 13px; }
        .auth-divider::before, .auth-divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }
        @media (max-width: 480px) {
            .auth-card { padding: 24px 20px; border-radius: 16px; }
            .auth-card h2 { font-size: 18px !important; }
            .auth-logo { font-size: 18px; margin-bottom: 4px; }
            .auth-subtitle { font-size: 13px; margin-bottom: 20px; }
        }
    </style>
</head>
<body>
    <div class="auth-page">
        <div class="auth-card fade-in" style="position: relative;">
            <button onclick="window.history.length > 1 ? window.history.back() : window.location.href = '{{ url('/') }}'" style="position: absolute; top: 20px; right: 20px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: var(--text-tertiary); transition: all 0.2s ease; background: var(--bg-tertiary);" onmouseover="this.style.color='var(--text-primary)'; this.style.transform='scale(1.1)';" onmouseout="this.style.color='var(--text-tertiary)'; this.style.transform='scale(1)';">
                <i class="ph ph-x" style="font-size: 18px;"></i>
            </button>
            <div class="auth-logo">
                <img src="{{ asset('images/peerly.png') }}" alt="Peerly Logo" style="height: 40px; width: auto;">
                Peerly
            </div>
            {{ $slot }}
        </div>
    </div>
</body>
</html>
