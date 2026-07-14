@extends('layouts.admin')

@section('admin.content')
<div class="bb-anim-fadein">
  <!-- Page Header -->
  <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-4">
    <div>
      <div class="bb-admin-title fs-4">Dashboard Admin</div>
      <div class="bb-admin-subtitle small">Ringkasan cepat dan aktivitas terbaru</div>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-bb-outline btn-sm btn-bb" type="button">
        <i class="bi bi-arrow-clockwise me-1"></i>Refresh
      </button>
    </div>
  </div>

  <!-- Summary Cards (6) -->
  <div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-2">
      <div class="bb-card-soft bb-hover-lift h-100 bb-summary-card bb-summary">
        <div class="bb-summary-icon mb-3"><span>📚</span></div>
        <div class="bb-summary-label">Total Buku</div>
        <div class="bb-summary-value">{{ $totalBooks ?? 0 }}</div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-2">
      <div class="bb-card-soft bb-hover-lift h-100 bb-summary-card bb-summary">
        <div class="bb-summary-icon mb-3"><span>📝</span></div>
        <div class="bb-summary-label">Total Kategori</div>
        <div class="bb-summary-value">{{ $totalCategories ?? 0 }}</div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-2">
      <div class="bb-card-soft bb-hover-lift h-100 bb-summary-card bb-summary">
        <div class="bb-summary-icon mb-3"><span>📖</span></div>
        <div class="bb-summary-label">Buku Dipinjam</div>
        <div class="bb-summary-value">{{ $totalBorrowed ?? 0 }}</div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-2">
      <div class="bb-card-soft bb-hover-lift h-100 bb-summary-card bb-summary">
        <div class="bb-summary-icon mb-3"><span>⚠</span></div>
        <div class="bb-summary-label">Buku Terlambat</div>
        <div class="bb-summary-value">{{ $totalOverdue ?? 0 }}</div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-2">
      <div class="bb-card-soft bb-hover-lift h-100 bb-summary-card bb-summary">
        <div class="bb-summary-icon mb-3"><span>👑</span></div>
        <div class="bb-summary-label">Membership Pending</div>
        <div class="bb-summary-value text-danger">{{ $membershipPendingCount ?? 0 }}</div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-2">
      <div class="bb-card-soft bb-hover-lift h-100 bb-summary-card bb-summary">
        <div class="bb-summary-icon mb-3"><span>👥</span></div>
        <div class="bb-summary-label">Total User</div>
        <div class="bb-summary-value">{{ $totalUsers ?? 0 }}</div>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="bb-quick bb-hover-lift mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
      <div>
        <div class="bb-admin-title">Quick Action</div>
        <div class="bb-admin-subtitle small">Akses cepat fitur administrasi</div>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-sm-6 col-lg-2">
        <a class="btn btn-bb-primary btn-bb-quick bb-quick-btn" href="{{ route('admin.books.index') }}">
          <i class="bi bi-plus-circle"></i>+ Tambah Buku
        </a>
      </div>
      <div class="col-sm-6 col-lg-2">
        <a class="btn btn-bb-primary btn-bb-quick bb-quick-btn" href="#" onclick="return false;">
          <i class="bi bi-plus-circle"></i>+ Tambah Kategori
        </a>
      </div>
      <div class="col-sm-6 col-lg-2">
        <a class="btn btn-bb-primary btn-bb-quick bb-quick-btn" href="{{ route('admin.users.index') }}">
          <i class="bi bi-plus-circle"></i>+ Tambah User
        </a>
      </div>
      <div class="col-sm-6 col-lg-2">
        <a class="btn btn-bb-primary bb-quick-btn" href="{{ route('admin.membership-requests.index') }}">
          <i class="bi bi-plus-circle"></i>Membership Request
        </a>
      </div>
      <div class="col-sm-6 col-lg-2">
        <a class="btn btn-bb-primary bb-quick-btn" href="{{ route('admin.borrowings.index') }}">
          <i class="bi bi-plus-circle"></i>Borrowing
        </a>
      </div>
    </div>
  </div>

  <!-- Recent Borrowings -->
  <div class="row g-3 mb-4">
    <div class="col-12">
      <div class="bb-card-soft bb-hover-lift p-0">
        <div class="p-4 pb-3">
          <div class="bb-admin-title">Borrowing Terbaru</div>
          <div class="bb-admin-subtitle small">Tampilan modern untuk monitoring status peminjaman</div>
        </div>
        <div class="px-4 pb-4">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 bb-admin-table">
              <thead>
                <tr>
                  <th>User</th>
                  <th>Buku</th>
                  <th>Tanggal Pinjam</th>
                  <th>Jatuh Tempo</th>
                  <th>Status</th>
                  <th class="text-center">Detail</th>
                </tr>
              </thead>
              <tbody>
@php
  // NOTE: DashboardController hanya mengirim membershipPendingCount saat ini.
  // Variabel berikut fallback ke array kosong/0 agar UI tetap tampil tanpa mengubah backend.
  $recentBorrowings = $recentBorrowings ?? [];
  $recentMembershipRequests = $recentMembershipRequests ?? [];
  $lowStockBooks = $lowStockBooks ?? [];

  $totalBooks = $totalBooks ?? 0;
  $totalCategories = $totalCategories ?? 0;
  $totalBorrowed = $totalBorrowed ?? 0;
  $totalOverdue = $totalOverdue ?? 0;
  $totalUsers = $totalUsers ?? 0;
@endphp

@forelse($recentBorrowings as $rb)
                  @php
                    $isOverdue = ($rb->status ?? '') !== 'returned' && isset($rb->due_date) && now() > $rb->due_date;
                    $statusBadge = ($rb->status ?? '') === 'returned' ? 'badge-dikembalikan' : ($isOverdue ? 'badge-terlambat' : 'badge-dipinjam');
                    $statusText = ($rb->status ?? '') === 'returned' ? 'Sudah Kembali' : ($isOverdue ? 'Terlambat' : 'Dipinjam');
                  @endphp

                  <tr>
                    <td>{{ $rb->user?->name ?? '-' }}</td>
                    <td>{{ $rb->book?->title ?? '-' }}</td>
                    <td class="text-muted">{{ $rb->borrowed_at?->format('d M Y') ?? '-' }}</td>
                    <td class="text-muted">{{ $rb->due_date?->format('d M Y') ?? '-' }}</td>
                    <td>
                      <span class="badge badge-bb {{ $statusBadge }}">{{ $statusText }}</span>
                    </td>
                    <td class="text-center">
                      <a class="btn btn-bb-outline btn-sm btn-bb" href="{{ route('admin.borrowings.index') }}">
                        Detail
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                      Tidak ada data borrowing terbaru
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Membership Request + Statistic/Alerts (right side) -->
  <div class="row g-3 mb-4">
    <div class="col-lg-4">
      <div class="bb-card-soft bb-hover-lift p-0">
        <div class="p-4 pb-3">
          <div class="bb-admin-title">Membership Request</div>
          <div class="bb-admin-subtitle small">5 request terbaru</div>
        </div>
        <div class="px-4 pb-4">
          <div class="d-flex flex-column gap-2">
            @forelse(($recentMembershipRequests ?? []) as $mr)
              <div class="d-flex align-items-center justify-content-between gap-2 bb-anim-fadein">
                <div>
                  <div class="fw-bold">{{ $mr->user?->name ?? '-' }}</div>
                  <div class="small text-muted">{{ $mr->plan_name ?? ($mr->membership_plan ?? 'Paket') }}</div>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <span class="badge badge-bb badge-pending">Pending</span>
                  <div class="d-flex gap-2">
                    <a class="btn btn-bb-primary btn-bb btn-sm" href="#" onclick="return false;">Approve</a>
                    <a class="btn btn-bb-outline btn-bb btn-sm" href="#" onclick="return false;">Reject</a>
                  </div>
                </div>
              </div>
            @empty
              <div class="alert alert-info mb-0">
                Tidak ada request membership terbaru.
              </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="row g-3">
        <!-- Statistic Chart -->
        <div class="col-12 col-lg-7">
          <div class="bb-card-soft bb-hover-lift p-4">
            <div class="d-flex align-items-start justify-content-between gap-3 mb-2">
              <div>
                <div class="bb-admin-title">Statistic Chart</div>
                <div class="bb-admin-subtitle small">Borrowing per bulan</div>
              </div>
              <span class="badge badge-basic">Dummy</span>
            </div>
            <div style="position:relative;height:260px;">
              <canvas id="bbBorrowingChart"></canvas>
            </div>
          </div>
        </div>

        <!-- Book Stock Alert -->
        <div class="col-12 col-lg-5">
          <div class="bb-card-soft bb-hover-lift p-4">
            <div class="d-flex align-items-start justify-content-between gap-3 mb-2">
              <div>
                <div class="bb-admin-title">Book Stock Alert</div>
                <div class="bb-admin-subtitle small">Stok kurang dari 3</div>
              </div>
              <span class="badge badge-terlambat badge-bb">Low</span>
            </div>
            <div class="d-flex flex-column gap-2">
              @forelse(($lowStockBooks ?? []) as $lsb)
                <div class="d-flex justify-content-between align-items-center gap-2">
                  <div class="fw-bold">{{ $lsb->title ?? '-' }}</div>
                  <span class="badge badge-terlambat badge-bb">{{ $lsb->stock ?? 0 }}</span>
                </div>
              @empty
                <div class="small text-muted">Belum ada data stok rendah.</div>
              @endforelse
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="bb-card-soft bb-hover-lift p-4 mt-3">
        <div class="bb-admin-title mb-2">Recent Activity</div>
        <div class="bb-admin-subtitle small mb-3">Timeline contoh (dummy)</div>
        <div class="bb-timeline">
          <div class="bb-timeline-item">
            <div class="bb-timeline-dot"></div>
            <div>
              <div class="fw-bold">✔ User meminjam buku</div>
              <div class="small text-muted">2 menit lalu</div>
            </div>
          </div>
          <div class="bb-timeline-item">
            <div class="bb-timeline-dot"></div>
            <div>
              <div class="fw-bold">✔ Membership disetujui</div>
              <div class="small text-muted">1 jam lalu</div>
            </div>
          </div>
          <div class="bb-timeline-item">
            <div class="bb-timeline-dot"></div>
            <div>
              <div class="fw-bold">✔ Buku dikembalikan</div>
              <div class="small text-muted">Kemarin</div>
            </div>
          </div>
          <div class="bb-timeline-item">
            <div class="bb-timeline-dot"></div>
            <div>
              <div class="fw-bold">✔ User baru registrasi</div>
              <div class="small text-muted">2 hari lalu</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chart.js + dummy data -->
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  (function(){
    const ctx = document.getElementById('bbBorrowingChart');
    if(!ctx) return;
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Jan','Feb','Mar','Apr','Mei','Jun'],
        datasets: [{
          label: 'Borrowings',
          data: [3, 8, 5, 10, 7, 12],
          tension: 0.35,
          borderColor: '#1b4fff',
          backgroundColor: 'rgba(27,79,255,.12)',
          fill: true,
          pointRadius: 3
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true, ticks: { precision: 0 } }
        }
      }
    });
  })();
</script>
@endpush
@endsection




