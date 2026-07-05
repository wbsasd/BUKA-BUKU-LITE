<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BUKA BUKU') }}</title>

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
                <ul class="navbar-nav ms-auto d-flex align-items-center">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Keluar
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </x-slot>
        </x-navbar>

        <div class="container-fluid">
            <div class="row">
                <aside class="col-lg-2 d-none d-lg-block bg-light p-3">
                    <x-sidebar>
                        <a class="nav-link" href="{{ route('home') }}">Beranda</a>
                        <a class="nav-link" href="#">Discover</a>
                        <a class="nav-link" href="#">Wishlist</a>
                        <a class="nav-link" href="#">Pengaturan</a>
                    </x-sidebar>
                </aside>

                <main class="col-lg-9 col-12 py-4">
                    @yield('content')
                </main>
            </div>
        </div>

        <x-footer />
    </div>
<!-- Bootstrap Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
