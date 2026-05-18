<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Peerly Admin</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <meta name="theme-color" content="#3b82f6">

    <!-- Phosphor Icons -->
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/bold/style.css">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/fill/style.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Admin sidebar */
        .admin-sidebar {
            width: 240px;
            flex-shrink: 0;
            border-right: 1px solid var(--border);
            padding: 24px 16px;
            height: calc(100vh - var(--navbar-h));
            position: sticky;
            top: var(--navbar-h);
            overflow-y: auto;
            background: var(--bg-secondary);
        }

        /* Mobile admin nav — horizontal tab bar at top of content */
        .admin-mobile-nav {
            display: none;
        }

        @media (max-width: 768px) {
            /* On mobile, make admin sidebar behave like a drawer (toggle with navbar button) */
            .admin-sidebar {
                display: none;
            }
            .admin-sidebar.mobile-open {
                display: flex;
                flex-direction: column;
                position: fixed;
                top: var(--navbar-h);
                left: 0;
                bottom: 0;
                width: 240px;
                z-index: 120;
                background: var(--bg-secondary);
                box-shadow: var(--shadow-lg);
            }

            .admin-mobile-nav {
                display: flex;
                gap: 4px;
                padding: 12px;
                overflow-x: auto;
                scrollbar-width: none;
                -webkit-overflow-scrolling: touch;
                border-bottom: 1px solid var(--border);
                background: var(--bg-secondary);
            }
            .admin-mobile-nav::-webkit-scrollbar { display: none; }

            .admin-mobile-nav a {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 8px 14px;
                border-radius: var(--radius-full);
                font-size: 13px;
                font-weight: 500;
                color: var(--text-secondary);
                white-space: nowrap;
                flex-shrink: 0;
                transition: var(--transition);
                background: var(--bg-tertiary);
            }
            .admin-mobile-nav a:hover {
                color: var(--text-primary);
            }
            .admin-mobile-nav a.active {
                background: var(--accent);
                color: white;
            }

            /* Make admin tables not break layout */
            .admin-content table {
                min-width: 500px;
            }

            /* Stack the manage admins form */
            .admin-manage-form {
                flex-direction: column !important;
            }

            /* Admin list items stack on mobile */
            .admin-list-item {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="app-layout">

        {{-- Navbar --}}
        @include('layouts.navigation')

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="flash-message flash-success"><i class="ph ph-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash-message flash-error"><i class="ph ph-warning-circle"></i> {{ session('error') }}</div>
        @endif

        {{-- Body --}}
        <div class="app-body">
            {{-- Admin Sidebar (desktop + mobile drawer) --}}
            <div class="admin-sidebar app-sidebar">
                <div class="sidebar-section">
                    <div class="sidebar-title">Admin Controls</div>
                    <ul>
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="ph ph-squares-four link-icon"></i> Dashboard Overview
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                <i class="ph ph-users link-icon"></i> Manage Users
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.posts.index') }}" class="sidebar-link {{ request()->routeIs('admin.posts.index') ? 'active' : '' }}">
                                <i class="ph ph-article link-icon"></i> Manage Posts
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.comments.index') }}" class="sidebar-link {{ request()->routeIs('admin.comments.index') ? 'active' : '' }}">
                                <i class="ph ph-chats link-icon"></i> Manage Comments
                            </a>
                        </li>
                        <li style="margin-top: 24px;">
                            <a href="/" class="sidebar-link">
                                <i class="ph ph-arrow-u-up-left link-icon"></i> Back to Community
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Main Content Area --}}
            <div style="flex: 1; min-width: 0; display: flex; flex-direction: column; overflow-x: hidden;">
                {{-- Mobile Nav Tabs (optional quick access) --}}
                <div class="admin-mobile-nav">
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="ph ph-squares-four"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                        <i class="ph ph-users"></i> Users
                    </a>
                    <a href="{{ route('admin.posts.index') }}" class="{{ request()->routeIs('admin.posts.index') ? 'active' : '' }}">
                        <i class="ph ph-article"></i> Posts
                    </a>
                    <a href="{{ route('admin.comments.index') }}" class="{{ request()->routeIs('admin.comments.index') ? 'active' : '' }}">
                        <i class="ph ph-chats"></i> Comments
                    </a>
                    <a href="/">
                        <i class="ph ph-arrow-u-up-left"></i> Back
                    </a>
                </div>

                {{-- Page Content --}}
                <main class="app-main admin-content" style="max-width: 1200px; width: 100%;">
                    {{ $slot }}
                </main>
            </div>
        </div>

    </div>
</body>
</html>
