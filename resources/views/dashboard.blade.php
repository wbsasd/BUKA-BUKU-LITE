@extends('layouts.app')

@section('content')

<div class="bb-dashboard-bg">
  <div class="bb-dashboard-wrap">
    <div class="row g-4">

      {{-- Main layout: left col-lg-8, right col-lg-4 --}}
      <div class="col-lg-8 col-12">

        {{-- Hero (only welcome + subtitle + CTA + illustration) --}}
        <section class="dashboard-hero bb-hero-modern mb-4">
          <div class="dashboard-hero-inner d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="dashboard-hero-contents">
              <div class="bb-hero-topline d-flex align-items-center gap-2 mb-2">
                <span class="bb-dot"></span>
                <span class="text-uppercase letter-spacing text-white-75 fw-semibold" style="font-size:12px;">Digital Library</span>
              </div>

              <h2 class="fw-bold mb-2">Halo, {{ Auth::user()?->name ?? 'Pengguna' }} 👋</h2>
              <p class="mb-0 text-white-75">Selamat datang kembali di <span class="fw-semibold">BUKA-BUKU-LITE</span>. Lanjutkan membaca dan temukan rekomendasi terbaru untuk Anda.</p>

              <div class="mt-4 bb-hero-cta">
                <a href="#continue-reading" class="btn btn-sm btn-bb-primary px-4">Continue Reading</a>
              </div>
            </div>

            <!-- <div class="bb-hero-illustration" aria-hidden="true">
              <div class="bb-hero-blob"></div>
              <div class="bb-hero-badge">
                <i class="bi bi-book-half"></i>
              </div>
            </div> -->
          </div>
        </section>

        {{-- Stat cards (MUST be outside hero) --}}
        <div class="row g-3 mb-4 bb-stats-grid">
          <div class="col-12 col-md-6 col-lg-3">
            <div class="bb-card bb-stat-card h-100">
              <div class="bb-stat-icon text-primary">
                <i class="bi bi-bookmark-check"></i>
              </div>
              <div>
                <div class="bb-stat-value" id="borrowing-count">{{ $borrowingCount ?? 0 }}</div>
                <div class="bb-stat-label">Buku Sedang Dipinjam</div>
              </div>
            </div>
          </div>

          <div class="col-12 col-md-6 col-lg-3">
            <div class="bb-card bb-stat-card h-100">
              <div class="bb-stat-icon text-warning">
                <i class="bi bi-gem"></i>
              </div>
              <div>
                <div class="bb-stat-value">{{ $membershipLabel ?? '—' }}</div>
                <div class="bb-stat-label">Membership</div>
              </div>
            </div>
          </div>

          <div class="col-12 col-md-6 col-lg-3">
            <div class="bb-card bb-stat-card h-100">
              <div class="bb-stat-icon text-danger">
                <i class="bi bi-cash"></i>
              </div>
              <div>
                <div class="bb-stat-value">{{ $totalDenda ?? 0 }}</div>
                <div class="bb-stat-label">Total Denda</div>
              </div>
            </div>
          </div>

          <div class="col-12 col-md-6 col-lg-3">
            <div class="bb-card bb-stat-card h-100">
              <div class="bb-stat-icon text-info">
                <i class="bi bi-journal-bookmark"></i>
              </div>
              <div>
                <div class="bb-stat-value">{{ $booksReadCount ?? 0 }}</div>
                <div class="bb-stat-label">Total Buku Dibaca</div>
              </div>
            </div>
          </div>
        </div>

        {{-- Continue Reading + Recommendations + Category + Timeline --}}
        <div class="row g-4">
          <div class="col-12">

            {{-- Continue Reading --}}
            <div id="continue-reading" class="mb-4">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 bb-section-title">Continue Reading</h5>
                <span class="small text-muted">Lanjutkan tepat dari progres terakhir Anda</span>
              </div>

              <div class="row g-3">
                @if($latestBooks->isNotEmpty())
                  @foreach($latestBooks->take(3) as $book)
                    <div class="col-12">
                      <div class="bb-card bb-continue-item p-3 p-md-4">
                        <div class="d-flex align-items-center gap-4">
                          <div class="bb-cover-lg">
                            <img
                              src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('images/placeholder-cover.svg') }}"
                              alt="cover"
                            >
                          </div>

                          <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                              <div class="min-w-0">
                                <div class="fw-semibold bb-truncate-2 bb-book-title">{{ $book->title }}</div>
                                <div class="small text-muted">{{ $book->author }}</div>
                              </div>
                              <a href="{{ route('reader', ['id' => $book->id]) }}" class="btn btn-sm btn-bb-outline">Lanjutkan</a>
                            </div>

                            <div class="mt-4">
                              <div class="bb-progress">
                                <div class="bb-progress-bar" style="width: 30%;"></div>
                              </div>
                              <div class="d-flex justify-content-between small text-muted mt-2">
                                <span>Hal. 12/200</span>
                                <span>30% selesai</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                @else
                  @include('user.empty-books-message')
                @endif
              </div>
            </div>

            {{-- Recommendation (cleaner card list) --}}
            <div class="mb-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0 bb-section-title">Recommendation</h5>
                <a href="#" class="small text-primary text-decoration-none">Lihat semua</a>
              </div>

              @if($recommendedBooks->isEmpty())
                @include('user.empty-books-message')
              @else
                <div class="row g-3">
                  @foreach($recommendedBooks->take(4) as $book)
                    <div class="col-6 col-md-3">
                      <div class="bb-hover-lift">
                        <x-book-card
                          :bookId="$book->id"
                          title="{{ $book->title }}"
                          author="{{ $book->author }}"
                          cover="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : null }}"
                          rating="4"
                        >
                          <div class="mt-2 d-grid">
                            <a href="{{ route('book.detail', ['id' => $book->id]) }}" class="btn btn-sm btn-bb-primary" style="border-radius:12px;">Lihat Detail</a>
                          </div>
                        </x-book-card>
                      </div>
                    </div>
                  @endforeach
                </div>
              @endif
            </div>

            {{-- Kategori --}}
            <div class="mb-4">
              <h5 class="mb-3 bb-section-title">Kategori</h5>
              <div class="d-flex gap-2 flex-wrap">
                @foreach($categories as $cat)
                  <a href="#" class="btn btn-outline-secondary btn-sm bb-pill">{{ $cat->name }}</a>
                @endforeach
              </div>
            </div>

            {{-- Activity Timeline --}}
            <div class="mb-2">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0 bb-section-title">Aktivitas Terbaru</h5>
                <span class="small text-muted">Ringkasan aktivitas Anda</span>
              </div>

              <div class="bb-timeline card">
                <div class="card-body">
                  <ul class="list-unstyled mb-0">
                    <li class="bb-timeline-item">
                      <span class="bb-timeline-dot bg-primary"></span>
                      <div class="bb-timeline-content">
                        <div class="fw-semibold">✔ Meminjam Buku</div>
                        <div class="small text-muted">Baru saja Anda meminjam buku favorit Anda.</div>
                      </div>
                    </li>
                    <li class="bb-timeline-item">
                      <span class="bb-timeline-dot bg-warning"></span>
                      <div class="bb-timeline-content">
                        <div class="fw-semibold">✔ Membership Premium Disetujui</div>
                        <div class="small text-muted">Akses premium aktif untuk akun Anda.</div>
                      </div>
                    </li>
                    <li class="bb-timeline-item">
                      <span class="bb-timeline-dot bg-success"></span>
                      <div class="bb-timeline-content">
                        <div class="fw-semibold">✔ Mengembalikan Buku</div>
                        <div class="small text-muted">Pengembalian telah diproses.</div>
                      </div>
                    </li>
                    <li class="bb-timeline-item">
                      <span class="bb-timeline-dot bg-danger"></span>
                      <div class="bb-timeline-content">
                        <div class="fw-semibold">✔ Membayar Denda</div>
                        <div class="small text-muted">Pembayaran denda berhasil.</div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            {{-- New section: Rekomendasi Buku (no carousel) --}}
            <div class="mt-4 mb-4">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- <h5 class="mb-0 bb-section-title">Rekomendasi Buku</h5>
                <span class="small text-muted">Pilihan populer untuk Anda</span> -->
              </div>

              <!-- @if($recommendedBooks->isEmpty())
                @include('user.empty-books-message')
              @else
                <div class="row g-3 bb-book-grid-5">
                  @foreach($recommendedBooks->take(10) as $book)
                    <div class="col-6 col-md-4 col-lg-2_4">
                      <div class="bb-hover-lift">
                        <x-book-card
                          :bookId="$book->id"
                          title="{{ $book->title }}"
                          author="{{ $book->author }}"
                          cover="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : null }}"
                          rating="4"
                        >
                          <div class="mt-2 d-grid">
                            <a href="{{ route('book.detail', ['id' => $book->id]) }}" class="btn btn-sm btn-bb-primary">Lihat</a>
                          </div>
                        </x-book-card>
                      </div>
                    </div>
                  @endforeach
                </div>
              @endif -->
            </div>

          </div>
        </div>
      </div>

      {{-- Right column --}}
      <div class="col-lg-4 col-12">

        {{-- Membership Card --}}
        <div class="mb-3">
          @php
            $currentUser = Auth::user();
            $hasPremiumAccess = $currentUser?->hasPremiumAccess() ?? false;
          @endphp

          @if(!$hasPremiumAccess)
            <x-membership-card role="Basic Member" name="{{ Auth::user()->name ?? 'User' }}">
              <div class="mt-3 d-grid">
                <a href="#" class="btn btn-sm btn-bb-outline" style="border-radius:12px;">Upgrade Sekarang</a>
              </div>
            </x-membership-card>
          @else
              @php
                $membership = \App\Models\MembershipUpgrade::query()
                  ->where('user_id', Auth::id())
                  ->whereIn('status', ['approved', 'active'])
                  ->orderByDesc('approved_at')
                  ->first();

                $statusMembership = '—';
                $startLabel = '-';
                $endLabel = '-';
                $remainingLabel = '-';

                if ($membership && $membership->end_date) {
                  $startLabel = $membership->start_date ? \Illuminate\Support\Carbon::parse($membership->start_date)->format('d M Y') : '-';
                  $end = \Illuminate\Support\Carbon::parse($membership->end_date)->startOfDay();
                  $endLabel = $end->format('d M Y');

                  if ($end->lt(now()->startOfDay())) {
                    $statusMembership = 'Expired';
                    $remainingLabel = $end->diffInDays(now()->startOfDay(), false);
                  } else {
                    $statusMembership = 'Active';
                    $remainingLabel = $end->diffInDays(now()->startOfDay(), false);
                  }
                }
              @endphp

              <x-membership-card role="👑 Premium Member" name="{{ Auth::user()->name ?? 'User' }}">
                <div class="mt-3 text-start">
                  <div class="small text-muted">Status Membership</div>
                  <div class="fw-semibold">{{ $statusMembership }}</div>

                  <div class="mt-2 small text-muted">Aktif sejak</div>
                  <div class="fw-semibold">{{ $startLabel }}</div>

                  <div class="mt-2 small text-muted">Berlaku sampai</div>
                  <div class="fw-semibold">{{ $endLabel }}</div>

                  <div class="mt-2 small text-muted">Sisa Hari Aktif</div>
                  <div class="fw-semibold">{{ $remainingLabel }} Hari</div>

                  <div class="mt-3 d-grid">
                    <a href="#" class="btn btn-sm btn-bb-primary" style="border-radius:12px;">Kelola Membership</a>
                  </div>
                </div>
              </x-membership-card>
          @endif
        </div>

        {{-- Existing right recommendation list, modernized --}}
        <div class="card mb-3 bb-card">
          <div class="card-body">
            <h5 class="mb-1 bb-section-title" style="font-size:16px;">Rekomendasi Untuk Anda</h5>
            <p class="small text-muted mb-3">Berdasarkan bacaan terakhir Anda</p>
            <ul class="list-unstyled small mb-0">
              @if($recommendedBooks->isEmpty())
                <li class="py-2 text-muted">@include('user.empty-books-message')</li>
              @else
                @foreach($recommendedBooks->take(3) as $book)
                  <li class="py-2 d-flex justify-content-between align-items-center border-bottom">
                    <div class="pe-2">
                      <div class="fw-semibold">{{ $book->title }}</div>
                      <div class="text-muted">{{ $book->author }}</div>
                    </div>
                    <a href="{{ route('reader', ['id' => $book->id]) }}" class="btn btn-sm btn-bb-outline" style="border-radius:12px;">Lihat</a>
                  </li>
                @endforeach
              @endif
            </ul>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@push('scripts')
<script>
// realtime dashboard stats polling
(function () {
  const borrowingEl = document.getElementById('borrowing-count');

  // Total denda + membership + books read stat values (stat cards only)
  const statValues = document.querySelectorAll('.bb-stats-grid .bb-stat-value');
  const borrowingStatEl = statValues[0] ?? null;
  const membershipLabelEl = statValues[1] ?? null;
  const totalDendaEl = statValues[2] ?? null;
  const booksReadCountEl = statValues[3] ?? null;

  if (!borrowingEl && !membershipLabelEl && !totalDendaEl && !booksReadCountEl) return;



  const url = @json(route('dashboard.stats.realtime'));
  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  const useCsrfHeader = Boolean(csrf);

  async function refresh() {
    try {
      const res = await fetch(url, {
        method: 'GET',
        headers: {
          'Accept': 'application/json',
          ...(useCsrfHeader ? { 'X-CSRF-TOKEN': csrf } : {})
        }
      });
      if (!res.ok) return;
      const data = await res.json();

      if (borrowingEl && typeof data.borrowingCount !== 'undefined') {
        borrowingEl.textContent = data.borrowingCount;
      }
      if (membershipLabelEl && typeof data.membershipLabel !== 'undefined') {
        membershipLabelEl.textContent = data.membershipLabel;
      }
      if (totalDendaEl && typeof data.totalDenda !== 'undefined') {
        totalDendaEl.textContent = data.totalDenda;
      }
      if (booksReadCountEl && typeof data.booksReadCount !== 'undefined') {
        booksReadCountEl.textContent = data.booksReadCount;
      }


    } catch (e) {
      // ignore
    }
  }

  // Count-up animation for all statistic cards (runs only once after page load)
  // Does not change HTML and does not add any external libraries.
  (function initCountUpOnLoad() {
    const statValueEls = document.querySelectorAll('.bb-stats-grid .bb-stat-value');
    if (!statValueEls || statValueEls.length === 0) return;

    const durationMs = 900;
    const startValue = 0;

    const easeOutCubic = (t) => 1 - Math.pow(1 - t, 3);

    function toNumber(text) {
      // remove non-digit separators (e.g., Rp1.000)
      const cleaned = String(text ?? '').replace(/[^0-9]/g, '');
      const n = parseInt(cleaned, 10);
      return Number.isFinite(n) ? n : 0;
    }


    statValueEls.forEach((el) => {
      const target = toNumber(el.textContent);
      if (!Number.isFinite(target)) return;

      // If already at 0 and no fine/values, still animate subtly but keep it lightweight
      const start = performance.now();

      function frame(now) {
        const t = Math.min(1, (now - start) / durationMs);
        const v = Math.round(startValue + (target - startValue) * easeOutCubic(t));
        el.textContent = v;
        if (t < 1) requestAnimationFrame(frame);
      }

      requestAnimationFrame(frame);
    });
  })();

  // First update right away, then every 30 seconds.
  refresh();
  setInterval(refresh, 30000);
})();
</script>
@endpush
@endsection







