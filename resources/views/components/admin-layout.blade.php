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
                            <a href="{{ route('admin.deletion-requests.index') }}" class="sidebar-link {{ request()->routeIs('admin.deletion-requests.index') ? 'active' : '' }}">
                                <i class="ph ph-user-minus link-icon"></i> Deletion Requests
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
                    <a href="{{ route('admin.deletion-requests.index') }}" class="{{ request()->routeIs('admin.deletion-requests.index') ? 'active' : '' }}">
                        <i class="ph ph-user-minus"></i> Deletions
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

        {{-- Custom Confirmation Modal --}}
        <div id="confirm-modal" class="confirm-modal-overlay" style="display: none;">
            <div class="confirm-modal-card">
                <div class="confirm-modal-header">
                    <i class="ph ph-warning-circle" style="font-size: 24px; color: var(--danger);"></i>
                    <h3>Confirm Action</h3>
                </div>
                <div class="confirm-modal-body">
                    <p id="confirm-modal-message">Are you sure you want to proceed?</p>
                </div>
                <div class="confirm-modal-footer">
                    <button id="confirm-modal-cancel" class="btn btn-ghost btn-sm">Cancel</button>
                    <button id="confirm-modal-confirm" class="btn btn-danger btn-sm" style="background: var(--danger); color: white; border: none; padding: 6px 16px; border-radius: var(--radius-md); font-weight: 600; cursor: pointer;">Confirm</button>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = document.getElementById('confirm-modal');
                if (!modal) return;

                const messageEl = document.getElementById('confirm-modal-message');
                const cancelBtn = document.getElementById('confirm-modal-cancel');
                const confirmBtn = document.getElementById('confirm-modal-confirm');
                let activeForm = null;

                document.addEventListener('submit', function (e) {
                    const form = e.target;
                    const onsubmitAttr = form.getAttribute('onsubmit');
                    
                    if (form.classList.contains('confirm-delete') || (onsubmitAttr && onsubmitAttr.includes('confirm('))) {
                        if (form === activeForm) {
                            return;
                        }

                        e.preventDefault();
                        activeForm = form;

                        let message = "Are you sure you want to delete this?";
                        if (onsubmitAttr) {
                            const match = onsubmitAttr.match(/confirm\(['"](.*?)['"]\)/);
                            if (match && match[1]) {
                                message = match[1];
                            }
                        }

                        form.dataset.originalOnsubmit = onsubmitAttr || '';
                        form.removeAttribute('onsubmit');

                        messageEl.textContent = message;
                        modal.style.display = 'flex';
                        modal.offsetHeight; // force reflow
                        modal.classList.add('show');
                    }
                });

                cancelBtn.addEventListener('click', function () {
                    closeModal();
                });

                confirmBtn.addEventListener('click', function () {
                    if (activeForm) {
                        const form = activeForm;
                        closeModal();
                        if (form.dataset.originalOnsubmit) {
                            form.setAttribute('onsubmit', form.dataset.originalOnsubmit);
                        }
                        form.submit();
                    }
                });

                modal.addEventListener('click', function (e) {
                    if (e.target === modal) {
                        closeModal();
                    }
                });

                function closeModal() {
                    modal.classList.remove('show');
                    setTimeout(() => {
                        modal.style.display = 'none';
                        if (activeForm) {
                            if (activeForm.dataset.originalOnsubmit) {
                                activeForm.setAttribute('onsubmit', activeForm.dataset.originalOnsubmit);
                            }
                            activeForm = null;
                        }
                    }, 200);
                }
            });
        </script>
    </div>
</body>
</html>
