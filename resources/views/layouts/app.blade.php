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
                    <ul class="navbar-nav ms-auto d-flex align-items-center gap-2">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                            </li>
                        @endif
                    @else
                        @php
                            $roleName = strtolower((Auth::user()->role ?? 'user'));
                            $isPremium = in_array($roleName, ['premium','anggota premium','premium member']);
                            $notifCount = 3; // dummy data
                        @endphp

                        <li class="nav-item">
                            <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                                <i class="bi bi-bell-fill"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" style="font-size:11px;">{{ $notifCount }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end p-2" style="min-width:340px;">
                                <div class="px-3 py-2">
                                    <div class="fw-semibold">Notifications</div>
                                    <div class="small text-muted">Aktivitas terbaru</div>
                                </div>
                                <hr class="my-2">

                                <div class="px-3 py-2">
                                    <div class="small text-muted fw-semibold text-uppercase mb-2">Membership</div>
                                    <div class="small">✔ Premium request approved</div>
                                </div>
                                <hr class="my-2">

                                <div class="px-3 py-2">
                                    <div class="small text-muted fw-semibold text-uppercase mb-2">Borrowing</div>
                                    <div class="small">✔ “Design Patterns” borrowed</div>
                                </div>
                                <hr class="my-2">

                                <div class="px-3 py-2">
                                    <div class="small text-muted fw-semibold text-uppercase mb-2">Warning</div>
                                    <div class="small">⚠ Due date soon (2 days)</div>
                                </div>
                                <hr class="my-2">

                                <div class="px-3 py-2">
                                    <div class="small text-muted fw-semibold text-uppercase mb-2">Denda</div>
                                    <div class="small">✔ Payment received</div>
                                </div>
                                <hr class="my-2">

                                <div class="px-3 py-2">
                                    <div class="small text-muted fw-semibold text-uppercase mb-2">Return</div>
                                    <div class="small">✔ Book returned successfully</div>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <span>{{ Auth::user()->name }}</span>
                                @if($isPremium)
                                    <span class="bb-badge-premium">👑 PREMIUM MEMBER</span>
                                @else
                                    <span class="bb-badge-basic">Basic Member</span>
                                @endif
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

        {{-- USER SIDEBAR (Hamburger) --}}
        <button
            id="hamburgerBtn"
            type="button"
            aria-label="Open sidebar"
            class="btn btn-light shadow-sm"
            style="position:fixed;top:0;left:0;z-index:1100; margin:16px;width:44px;height:44px; border-radius:12px;"
        >
            <span class="fs-4" aria-hidden="true">☰</span>
        </button>

        <div
            id="sidebarBackdrop"
            class="position-fixed top-0 start-0 w-100 h-100 d-none"
            style="background:rgba(0,0,0,0.35);z-index:1040;"
        ></div>

        <style>
            /* Sidebar slider from left */
            #userSidebar {
                position: fixed;
                left: -260px;
                width: 260px;
                height: 100%;
                transition: .3s ease;
                z-index: 1050;
                top: 0;
            }
            #userSidebar.open {
                left: 0;
            }
        </style>

        {{-- Keep sidebar HTML structure (do not move existing layout/grid) --}}
        <aside id="userSidebar" class="vh-100 bg-white border-end p-3 shadow-lg rounded-xl">
            <div class="mb-5">
                <h5 class="mb-0"></h5>
            </div>
            <nav class="nav nav-pills flex-column">
                <a class="nav-link" href="{{ route('home') }}">Beranda</a>
                <a class="nav-link" href="#">Discover</a>
                <a class="nav-link" href="#">Wishlist</a>
                <a class="nav-link" href="{{ route('borrow.history') }}">Order</a>
                <a class="nav-link" href="#">Pengaturan</a>

                <hr class="my-3">

                <a
                    class="nav-link"
                    href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();"
                >
                    Logout
                </a>

                <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </nav>
        </aside>

        {{-- content --}}
        <div class="d-flex justify-content-center">
            <div class="container-fluid" style="max-width:1300px;width:100%;padding:24px;">
        <main style="width:100%;">
                    @yield('content')
                </main>
            </div>
        </div>


        {{-- sidebar toggle logic --}}
        <script>
            (function () {
                const btn = document.getElementById('hamburgerBtn');
                const sidebar = document.getElementById('userSidebar');
                const backdrop = document.getElementById('sidebarBackdrop');

                if (!btn || !sidebar || !backdrop) return;

                function openSidebar() {
                    sidebar.classList.add('open');
                    backdrop.classList.remove('d-none');
                }

                function closeSidebar() {
                    sidebar.classList.remove('open');
                    backdrop.classList.add('d-none');
                }

                btn.addEventListener('click', function () {
                    if (sidebar.classList.contains('open')) closeSidebar();
                    else openSidebar();
                });

                backdrop.addEventListener('click', function () {
                    closeSidebar();
                });

                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') closeSidebar();
                });
            })();
        </script>

        <x-footer />
    </div>
<!-- Bootstrap Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
