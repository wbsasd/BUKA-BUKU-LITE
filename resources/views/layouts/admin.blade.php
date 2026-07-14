<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    @stack('styles')

    <!-- Design system CSS -->
    <link href="{{ asset('css/design-system.css') }}" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @else
        <!-- Vite manifest not found — loading without Vite (dev fallback). -->
    @endif
</head>
<body class="bg-light">
    <div class="d-flex" style="min-height: 100vh;">
        <!-- Admin Sidebar (admin-only) -->
        <aside class="bg-white border-end" style="width: 260px;">
            <div class="p-3">
                <div class="mb-3">
                    <div class="fw-semibold fs-5">{{ config('app.name', 'Buka Buku') }}</div>
                    <div class="text-muted small">Admin Panel</div>
                </div>

                <x-sidebar title="Admin">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>

                    <a class="nav-link" href="{{ route('admin.books.index') }}">Books</a>
                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Categories</a>
                    <a class="nav-link" href="{{ route('admin.borrowings.index') }}" >Borrowings</a>

                    <a class="nav-link" href="{{ route('admin.memberships.index') }}">Membership</a>
                    <a class="nav-link" href="{{ route('admin.membership-requests.index') }}">Permintaan Registrasi</a>

                    <a class="nav-link" href="{{ route('admin.reports.index') }}">Reports</a>
                    <a class="nav-link" href="{{ route('admin.users.index') }}">Users</a>


                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Settings</a>
                </x-sidebar>
            </div>
        </aside>

        <!-- Admin Main Column -->
        <div class="flex-grow-1">
            <!-- Admin Topbar -->
            <header class="bg-white border-bottom">
                <div class="container-fluid py-3 px-3 px-md-4 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="fw-semibold">Panel Administrasi</div>
                        <div class="text-muted small">Kelola data perpustakaan</div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        @auth
                            <div class="text-end">
                                <div class="small text-muted">Logged in as</div>
                                <div class="fw-semibold">{{ Auth::user()?->name }}</div>
                            </div>
                        @endauth

                        <form method="POST" action="{{ route('admin.logout.legacy') }}" class="m-0">
                            @csrf
                            <button class="btn btn-outline-secondary btn-sm" type="submit">Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Admin Content -->
            <main class="container-fluid py-4">
                @yield('admin.content')
            </main>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

