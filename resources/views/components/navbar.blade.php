@props(['brand' => 'BUKA BUKU'])

<nav class="navbar navbar-expand-lg bb-navbar fixed-top bg-white border-bottom">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <span class="fw-semibold">{{ $brand }}</span>
        </a>

        <button
            class="navbar-toggler border-0 shadow-sm"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link bb-nav-link" href="{{ route('home') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link bb-nav-link" href="#">Discover</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link bb-nav-link" href="#">Membership</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3 flex-wrap flex-lg-nowrap">
                {{-- RIGHT SIDE --}}
                @guest
                    @if(Route::has('login'))
                        <a class="btn btn-primary bb-rounded-12 px-3 bb-hover-lift" href="{{ route('login') }}">Masuk</a>
                    @endif
                @else
                    @php
                        /** @var \App\Models\User $user */
                        $user = Auth::user();
                        $isPremium = $user?->hasPremiumAccess() ?? false;
                        $membershipLabel = $isPremium ? 'Premium' : 'Basic';
                        $notifCount = 3; // dummy

                        $avatarUrl = $user->profile_photo
                            ? asset('storage/'.$user->profile_photo)
                            : asset('images/avatar-default.svg');

                        $dashboardUrl = Route::has('dashboard') ? route('dashboard') : '#';
                        $borrowHistoryUrl = Route::has('borrow.history') ? route('borrow.history') : '#';
                        $membershipUrl = Route::has('membership') ? route('membership') : (Route::has('membership.upgrade.plans') ? route('membership.upgrade.plans') : '#');
                        $wishlistUrl = Route::has('wishlist') ? route('wishlist') : '#';
                        $settingsUrl = Route::has('settings') ? route('settings') : '#';
                    @endphp

                    {{-- Notification (dummy) --}}
                    <div class="dropdown">
                        <a class="nav-link position-relative bb-icon-muted" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                            <i class="bi bi-bell-fill fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" style="font-size:11px;">{{ $notifCount }}</span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end bb-dropdown-fade bb-notif-dropdown">
                            <div class="px-3 py-2">
                                <div class="fw-semibold">Notifications</div>
                                <div class="small text-muted">Aktivitas terbaru</div>
                            </div>
                            <hr class="my-2">
                            <div class="px-3 py-2"><div class="small text-muted">✔ Premium request approved</div></div>
                            <div class="px-3 py-2"><div class="small text-muted">✔ “Design Patterns” borrowed</div></div>
                            <div class="px-3 py-2"><div class="small text-muted">⚠ Due date soon (2 days)</div></div>
                        </div>
                    </div>

                    {{-- Avatar dropdown --}}
                    <div class="dropdown">
                        <a
                            id="navbarDropdown"
                            class="d-flex align-items-center text-decoration-none"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            aria-haspopup="true"
                        >
                            <img src="{{ $avatarUrl }}" alt="Avatar" class="bb-avatar" />
                        </a>

                        <div class="dropdown-menu dropdown-menu-end bb-avatar-dropdown bb-dropdown-fade" aria-labelledby="navbarDropdown">
                            <div class="px-3 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $avatarUrl }}" alt="Avatar" class="bb-avatar-lg" />
                                    <div>
                                        <div class="fw-bold bb-truncate">{{ $user->name }}</div>
                                        <div class="small text-muted bb-truncate">{{ $user->email }}</div>
                                        <div class="mt-2">
                                            <span class="badge {{ $isPremium ? 'bg-warning text-dark' : 'bg-primary' }}">{{ $membershipLabel }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-2">

                            <div class="py-1 px-1">
                                <a class="dropdown-item bb-hover" href="{{ $dashboardUrl }}">Dashboard</a>

                                <a class="dropdown-item bb-hover {{ $borrowHistoryUrl === '#' ? 'disabled' : '' }}" href="{{ $borrowHistoryUrl }}" {{ $borrowHistoryUrl === '#' ? 'aria-disabled="true" tabindex="-1"' : '' }}>
                                    Riwayat Peminjaman
                                </a>

                                <a class="dropdown-item bb-hover {{ $membershipUrl === '#' ? 'disabled' : '' }}" href="{{ $membershipUrl }}" {{ $membershipUrl === '#' ? 'aria-disabled="true" tabindex="-1"' : '' }}>
                                    Membership
                                </a>

                                <a class="dropdown-item bb-hover {{ $wishlistUrl === '#' ? 'disabled' : '' }}" href="{{ $wishlistUrl }}" {{ $wishlistUrl === '#' ? 'aria-disabled="true" tabindex="-1"' : '' }}>
                                    Wishlist
                                </a>

                                <a class="dropdown-item bb-hover {{ $settingsUrl === '#' ? 'disabled' : '' }}" href="{{ $settingsUrl }}" {{ $settingsUrl === '#' ? 'aria-disabled="true" tabindex="-1"' : '' }}>
                                    Pengaturan
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item bb-hover" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

