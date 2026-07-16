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
    <link href="{{ asset('css/admin-design-system.css') }}" rel="stylesheet">


    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @else
        <!-- Vite manifest not found — loading without Vite (dev fallback). -->
    @endif
</head>
<body class="bg-light">
    <div class="d-flex" style="min-height: 100vh;">
        <!-- Admin Sidebar (admin-only) -->
        <aside class="bb-desktop-sidebar" style="width:250px;">
            <x-sidebar title="Admin">

                <a class="bb-nav-link {{ request()->routeIs('admin.dashboard') ? 'bb-active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <span class="bb-icon">📊</span>
                    <span>Dashboard</span>
                </a>

                <a class="bb-nav-link {{ request()->routeIs('admin.books.*') ? 'bb-active' : '' }}" href="{{ route('admin.books.index') }}">
                    <span class="bb-icon">📚</span>
                    <span>Books</span>
                </a>

                <!-- <a class="bb-nav-link disabled" href="#" tabindex="-1" aria-disabled="true">
                    <span class="bb-icon">🗂</span>
                    <span>Categories</span>
                </a> -->

                <a class="bb-nav-link {{ request()->routeIs('admin.borrowings.*') ? 'bb-active' : '' }}" href="{{ route('admin.borrowings.index') }}">
                    <span class="bb-icon">📖</span>
                    <span>Borrowings</span>
                </a>

                <a class="bb-nav-link {{ request()->routeIs('admin.memberships.*') ? 'bb-active' : '' }}" href="{{ route('admin.memberships.index') }}">
                    <span class="bb-icon">👑</span>
                    <span>Membership</span>
                </a>

                <a class="bb-nav-link {{ request()->routeIs('admin.membership-requests.*') ? 'bb-active' : '' }}" href="{{ route('admin.membership-requests.index') }}">
                    <span class="bb-icon">📝</span>
                    <span>Permintaan Registrasi</span>
                </a>

                <!-- <a class="bb-nav-link {{ request()->routeIs('admin.reports.*') ? 'bb-active' : '' }}" href="{{ route('admin.reports.index') }}">
                    <span class="bb-icon">📈</span>
                    <span>Reports</span>
                </a> -->

                <a class="bb-nav-link {{ request()->routeIs('admin.users.*') ? 'bb-active' : '' }}" href="{{ route('admin.users.index') }}">
                    <span class="bb-icon">👥</span>
                    <span>Users</span>
                </a>

                <!-- <a class="bb-nav-link disabled" href="#" tabindex="-1" aria-disabled="true">
                    <span class="bb-icon">⚙</span>
                    <span>Settings</span>
                </a> -->
            </x-sidebar>
        </aside>


        <!-- Admin Main Column -->
        <div class="flex-grow-1">
            <!-- Admin Topbar -->
            <header class="bb-topbar">
                <div class="container-fluid py-3 px-3 px-md-4 d-flex align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-bb-outline btn-sm d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#bbSidebarOffcanvas" aria-controls="bbSidebarOffcanvas">
                            <i class="bi bi-list"></i>
                        </button>
                        <button class="btn btn-bb-outline btn-sm d-none d-md-inline-flex" type="button" data-bs-toggle="collapse" data-bs-target="#bbAdminSearch" aria-expanded="false">
                            <i class="bi bi-search"></i>
                        </button>
                        <div>
                            <div class="bb-admin-title">Dashboard Admin</div>
                            <div class="bb-admin-subtitle small">Library Management System</div>
                        </div>
                    </div>

                    <!-- <div class="bb-search d-none d-lg-block">
                        <div class="input-group">
                            <span class="input-group-text bg-white" style="border-radius:14px 0 0 14px; border-right:0;">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Search... (dummy)" aria-label="Search" style="border-radius:0 14px 14px 0; border-left:0;">
                        </div>
                    </div> -->

                    <div class="d-flex align-items-center gap-2">
                        <!-- Notifications (dummy) -->
                        <div class="dropdown">
                            <button class="btn btn-bb-outline bb-icon-btn d-flex align-items-center justify-content-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end bb-notif-list">
                                <div class="fw-bold px-3 pt-2 pb-1">Notifications</div>
                                <div class="px-3 small text-muted">Dummy data</div>
                                <div class="bb-anim-fadein px-3 py-2">
                                    <div class="bb-notif-item">✅ Membership request pending</div>
                                    <div class="bb-notif-item">📖 Borrowing approaching due date</div>
                                </div>
                            </div>
                        </div>

                        <!-- Avatar dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-bb-outline bb-icon-btn d-flex align-items-center justify-content-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">Profile</a>
                                <a class="dropdown-item" href="#">Settings</a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('admin.logout.legacy') }}" class="m-0">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Mobile sidebar offcanvas -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="bbSidebarOffcanvas" aria-labelledby="bbSidebarOffcanvasLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="bbSidebarOffcanvasLabel">Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body p-0">
                    <x-sidebar title="Admin">
                        <a class="bb-nav-link {{ request()->routeIs('admin.dashboard') ? 'bb-active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <span class="bb-icon">📊</span>
                            <span>Dashboard</span>
                        </a>

                        <a class="bb-nav-link {{ request()->routeIs('admin.books.*') ? 'bb-active' : '' }}" href="{{ route('admin.books.index') }}">
                            <span class="bb-icon">📚</span>
                            <span>Books</span>
                        </a>

                        <a class="bb-nav-link disabled" href="#" tabindex="-1" aria-disabled="true">
                            <span class="bb-icon">🗂</span>
                            <span>Categories</span>
                        </a>

                        <a class="bb-nav-link {{ request()->routeIs('admin.borrowings.*') ? 'bb-active' : '' }}" href="{{ route('admin.borrowings.index') }}">
                            <span class="bb-icon">📖</span>
                            <span>Borrowings</span>
                        </a>

                        <a class="bb-nav-link {{ request()->routeIs('admin.memberships.*') ? 'bb-active' : '' }}" href="{{ route('admin.memberships.index') }}">
                            <span class="bb-icon">👑</span>
                            <span>Membership</span>
                        </a>

                        <a class="bb-nav-link {{ request()->routeIs('admin.membership-requests.*') ? 'bb-active' : '' }}" href="{{ route('admin.membership-requests.index') }}">
                            <span class="bb-icon">📝</span>
                            <span>Permintaan Registrasi</span>
                        </a>

                        <a class="bb-nav-link {{ request()->routeIs('admin.reports.*') ? 'bb-active' : '' }}" href="{{ route('admin.reports.index') }}">
                            <span class="bb-icon">📈</span>
                            <span>Reports</span>
                        </a>

                        <a class="bb-nav-link {{ request()->routeIs('admin.users.*') ? 'bb-active' : '' }}" href="{{ route('admin.users.index') }}">
                            <span class="bb-icon">👥</span>
                            <span>Users</span>
                        </a>

                        <a class="bb-nav-link disabled" href="#" tabindex="-1" aria-disabled="true">
                            <span class="bb-icon">⚙</span>
                            <span>Settings</span>
                        </a>
                    </x-sidebar>
                </div>
            </div>


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

