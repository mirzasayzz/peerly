<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Peerly — Online Student Community Forum. Share resources, discuss topics, and connect with fellow students.">

    <title>{{ $title ?? 'Peerly — Student Community Forum' }}</title>

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

    </div>
</body>
</html>
