<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BUKA BUKU LITE') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Icons (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Page styles -->
    @stack('styles')

    <!-- Design system CSS (font-family etc.) -->
    <link href="{{ asset('css/design-system.css') }}" rel="stylesheet">

    <!-- Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @else
        <!-- Vite manifest not found — loading without Vite (dev fallback). -->
        <!-- If you want to enable Vite dev server, run `npm install` and `npm run dev`. -->
    @endif
</head>
<body>
    <div id="app">
        <x-navbar>
            {{-- left slot: nav items (optional) --}}
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}">Beranda</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Discover</a>
            </li>

            <x-slot name="right">
                {{-- NOTE: navbar right side is handled inside components/navbar.blade.php --}}
            </x-slot>
        </x-navbar>

        {{-- content --}}
        <div class="d-flex justify-content-center">
            <div class="container-fluid" style="max-width:1450px;width:100%;padding:24px; padding-top:96px;">
                <main style="width:100%;">
                    @yield('content')
                </main>
            </div>
        </div>

        <x-footer />

        {{-- Bootstrap Bundle (includes Popper) --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
    </div>
</body>
</html>

