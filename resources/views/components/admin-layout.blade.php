<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Peerly Admin Portal</title>

    <!-- Phosphor Icons -->
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/bold/style.css">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/fill/style.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            {{-- Admin Sidebar Placeholder (if needed in future) --}}
            <div style="width: 240px; border-right: 1px solid var(--border); padding: 24px 16px; height: calc(100vh - var(--navbar-h)); position: sticky; top: var(--navbar-h); overflow-y: auto;">
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

            {{-- Main Content --}}
            <main class="app-main" style="max-width: 1200px; margin: 0 auto; width: 100%;">
                {{ $slot }}
            </main>
        </div>

    </div>
</body>
</html>
