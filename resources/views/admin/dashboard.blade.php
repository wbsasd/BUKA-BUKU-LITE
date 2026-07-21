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

    <!-- <div class="col-sm-6 col-xl-2">
      <div class="bb-card-soft bb-hover-lift h-100 bb-summary-card bb-summary">
        <div class="bb-summary-icon mb-3"><span>📝</span></div>
        <div class="bb-summary-label">Total Kategori</div>
        <div class="bb-summary-value">{{ $totalCategories ?? 0 }}</div>
      </div>
    </div> -->

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
              @php
                $status = $mr->status ?? 'pending';
                $statusText = match($status) {
                  'approved' => 'Approved',
                  'rejected' => 'Rejected',
                  default => 'Pending',
                };
                $badgeClass = match($status) {
                  'approved' => 'badge-success',
                  'rejected' => 'badge-danger',
                  default => 'badge-pending',
                };

                // Plan label
                $planKey = \App\Models\MembershipUpgrade::planKey((int)($mr->months ?? 0));
                $planLabel = isset($mr->months) ? $planKey : ($mr->plan_name ?? ($mr->membership_plan ?? 'Paket'));

                // Potential payment proof fields (best-effort, no schema change)
                $paymentProof = null;
                foreach (['payment_proof','payment_evidence','proof','payment_image','payment_attachment','bukti_pembayaran','payment_receipt'] as $field) {
                  if (isset($mr->$field) && $mr->$field) { $paymentProof = $mr->$field; break; }
                }
              @endphp

              <div class="d-flex align-items-center justify-content-between gap-2 bb-anim-fadein">
                <div>
                  <div class="fw-bold">{{ $mr->user?->name ?? '-' }}</div>
                  <div class="small text-muted">{{ $planLabel }}</div>
                </div>

                <div class="d-flex align-items-center gap-2">
                  <span class="badge badge-bb {{ $badgeClass }}">{{ $statusText }}</span>
                  <div class="d-flex gap-2">
                    <button type="button"
                      class="btn btn-bb-outline btn-bb btn-sm"
                      data-bs-toggle="modal"
                      data-bs-target="#membershipDetailModal"
                      data-user-name="{{ $mr->user?->name ?? '-' }}"
                      data-user-email="{{ $mr->user?->email ?? '-' }}"
                      data-plan="{{ $planLabel }}"
                      data-duration="{{ $mr->months ?? '-' }} bulan"
                      data-price="Rp{{ number_format((int)($mr->amount ?? 0), 0, ',', '.') }}"
                      data-payment-method="{{ $mr->payment_method ?? '-' }}"
                      data-payment-status="{{ ucfirst($mr->payment_status ?? 'unpaid') }}"
                      data-membership-status="{{ ucfirst($statusText) }}"
                      data-requested-at="{{ $mr->requested_at?->format('d M Y H:i') ?? '-' }}"
                      data-payment-proof="{{ $paymentProof ?? '' }}">
                      Detail
                    </button>

                    @if(($mr->status ?? 'pending') === 'pending')
                      <form method="POST" action="{{ route('admin.memberships.approve', $mr) }}" class="m-0">
                        @csrf
                        <button class="btn btn-bb-primary btn-bb btn-sm" type="submit">Approve</button>
                      </form>

                      <form method="POST" action="{{ route('admin.memberships.reject', $mr) }}" class="m-0">
                        @csrf
                        <button class="btn btn-bb-outline btn-bb btn-sm" type="submit">Reject</button>
                      </form>
                    @else
                      <span class="small text-muted">Actions unavailable</span>
                    @endif
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

          @forelse(($recentActivities ?? []) as $act)
            <div class="bb-timeline-item">
              <div class="bb-timeline-dot"></div>
              <div>
                <div class="fw-bold">
                  @if(!empty($act['icon']))
                    <i class="{{ $act['icon'] }} me-2"></i>
                  @endif
                  {{ $act['title'] ?? '-' }}
                </div>
                <div class="small text-muted">
                  {{ \Carbon\Carbon::parse($act['created_at'])->diffForHumans() }}
                </div>
                @if(!empty($act['description']))
                  <div class="small text-muted">{{ $act['description'] }}</div>
                @endif
              </div>
            </div>
          @empty
            <div class="small text-muted py-2">Tidak ada aktivitas terbaru.</div>
          @endforelse

        </div>
      </div>

    </div>
  </div>
</div>

  
  <!-- Membership Detail Modal (top-level render for correct stacking/pointer events) -->
  <div class="modal fade" id="membershipDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0">
        <div class="modal-header border-0" style="background: rgba(0,123,255,.08);">
          <h5 class="modal-title fw-bold">
            <i class="bi bi-info-circle me-2"></i>Detail Membership Request
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <div class="text-muted small">Nama User</div>
              <div class="fw-semibold" id="mdUserName">-</div>
            </div>
            <div class="col-md-6">
              <div class="text-muted small">Email</div>
              <div class="fw-semibold" id="mdUserEmail">-</div>
            </div>

            <div class="col-md-6">
              <div class="text-muted small">Paket Membership</div>
              <div class="fw-semibold" id="mdPlan">-</div>
            </div>
            <div class="col-md-6">
              <div class="text-muted small">Durasi</div>
              <div class="fw-semibold" id="mdDuration">-</div>
            </div>

            <div class="col-md-6">
              <div class="text-muted small">Harga</div>
              <div class="fw-semibold" id="mdPrice">-</div>
            </div>
            <div class="col-md-6">
              <div class="text-muted small">Metode Pembayaran</div>
              <div class="fw-semibold" id="mdPaymentMethod">-</div>
            </div>

            <div class="col-md-6">
              <div class="text-muted small">Status Pembayaran</div>
              <div class="fw-semibold" id="mdPaymentStatus">-</div>
            </div>
            <div class="col-md-6">
              <div class="text-muted small">Status Membership</div>
              <div class="fw-semibold" id="mdMembershipStatus">-</div>
            </div>
            <div class="col-12">
              <div class="text-muted small">Tanggal Request</div>
              <div class="fw-semibold" id="mdRequestedAt">-</div>
            </div>

            <div class="col-12 mt-2">
              <div class="text-muted small mb-1">Bukti Pembayaran</div>
              <div id="mdPaymentProofBox" class="small text-muted">Belum upload bukti pembayaran.</div>
              <div id="mdPaymentProofPreview" class="mt-2" style="display:none;">
                <img id="mdPaymentProofImg" alt="Bukti Pembayaran" style="max-width:100%;border-radius:10px;" />
                <div class="mt-2">
                  <a id="mdPaymentProofLink" class="btn btn-sm btn-bb-outline" href="#" target="_blank" rel="noopener">Lihat File</a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer border-0">
          <button type="button" class="btn btn-bb-outline" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

@push('scripts')
  <script>
    (function(){
      const modalEl = document.getElementById('membershipDetailModal');
      if(!modalEl) return;

      // Use Bootstrap's official API (single instance)
      const modal = bootstrap.Modal.getOrCreateInstance(modalEl, {
        backdrop: true,
        keyboard: true
      });

      modalEl.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if(!button) return;

        const get = (id, attr) => {
          const el = document.getElementById(id);
          if(el) el.textContent = button.getAttribute(attr) || '-';
        };

        get('mdUserName', 'data-user-name');
        get('mdUserEmail', 'data-user-email');
        get('mdPlan', 'data-plan');
        get('mdDuration', 'data-duration');
        get('mdPrice', 'data-price');
        get('mdPaymentMethod', 'data-payment-method');
        get('mdPaymentStatus', 'data-payment-status');
        get('mdMembershipStatus', 'data-membership-status');
        get('mdRequestedAt', 'data-requested-at');

        const proof = button.getAttribute('data-payment-proof') || '';
        const box = document.getElementById('mdPaymentProofBox');
        const preview = document.getElementById('mdPaymentProofPreview');
        const img = document.getElementById('mdPaymentProofImg');
        const link = document.getElementById('mdPaymentProofLink');

        if (!proof || proof.trim() === '') {
          if(box) box.textContent = 'Belum upload bukti pembayaran.';
          if(preview) preview.style.display = 'none';
          if(img) img.removeAttribute('src');
          if(link) link.href = '#';
          return;
        }

        if(box) box.textContent = '';
        if(preview) preview.style.display = 'block';

        if(img) img.src = proof;
        if(link) link.href = proof;
      });

      // Cleanup to prevent stuck-backdrop / unclickable page
      modalEl.addEventListener('hidden.bs.modal', function () {
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());

        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';

        // Ensure Bootstrap internal state reset safely
        try {
          const inst = bootstrap.Modal.getInstance(modalEl);
          inst?.dispose?.();
        } catch (e) {
          // no-op
        }
      });
    })();
  </script>

  <!-- Chart.js + dynamic data -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    (function(){
      const ctx = document.getElementById('bbBorrowingChart');
      if(!ctx) return;

      const chartLabels = @json($chartLabels);
      const chartValues = @json($chartValues);

      new Chart(ctx, {
        type: 'line',
        data: {
          labels: chartLabels,
          datasets: [{
            label: 'Borrowings',
            data: chartValues,
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

