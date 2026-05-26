<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Peerly | Online Student Community Forum. Share resources, discuss topics, and connect with fellow students.">

    <title>{{ $title ?? 'Peerly | Student Community Forum' }}</title>

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
            {{-- Sidebar --}}
            @include('layouts.sidebar')

            {{-- Main Content --}}
            <main class="app-main">
                {{ $slot }}
            </main>
        </div>

        {{-- Custom Confirmation Modal --}}
        <div id="confirm-modal" class="confirm-modal-overlay" style="display: none;">
            <div class="confirm-modal-card">
                <div class="confirm-modal-header">
                    <i class="ph ph-warning-circle" style="font-size: 24px; color: var(--danger);"></i>
                    <h3>Confirm Delete</h3>
                </div>
                <div class="confirm-modal-body">
                    <p id="confirm-modal-message">Are you sure you want to delete this?</p>
                </div>
                <div class="confirm-modal-footer">
                    <button id="confirm-modal-cancel" class="btn btn-ghost btn-sm">Cancel</button>
                    <button id="confirm-modal-confirm" class="btn btn-danger btn-sm" style="background: var(--danger); color: white; border: none; padding: 6px 16px; border-radius: var(--radius-md); font-weight: 600; cursor: pointer;">Delete</button>
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
