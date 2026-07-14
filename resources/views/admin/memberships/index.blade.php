@extends('layouts.admin')

@section('admin.content')

    @php
        $pendingCount = $stats['pending'] ?? 0;
        $approvedCount = $stats['approved'] ?? 0;
        $rejectedCount = $stats['rejected'] ?? 0;
        $expiredCount = $stats['expired'] ?? 0;

        $requestCount = $memberships?->total() ?? 0;
    @endphp

    <!-- Page Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-start gap-3">
            <div>
                <h3 class="fw-bold mb-1">Membership</h3>
                <p class="text-muted small mb-0">Kelola seluruh permintaan upgrade Membership Premium.</p>
            </div>
        </div>
    </div>

    <!-- Dashboard Cards (Pending/Approved/Rejected/Expired) -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-2">⏳ Pending</p>
                    <h3 class="fw-bold mb-0">{{ $pendingCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-2">✅ Approved</p>
                    <h3 class="fw-bold mb-0">{{ $approvedCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-2">❌ Rejected</p>
                    <h3 class="fw-bold mb-0">{{ $rejectedCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-2">⏲️ Expired</p>
                    <h3 class="fw-bold mb-0">{{ $expiredCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters (Search + Status) -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.memberships.index') }}" class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari Nama/Email/Paket" value="{{ $q ?? request('q') }}" />
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        @php
                            $current = $statusFilter ?? request('status', 'all');
                        @endphp
                        <option value="all" @selected($current === 'all')>Semua</option>
                        <option value="pending" @selected($current === 'pending')>Pending</option>
                        <option value="active" @selected($current === 'active')>Approved</option>
                        <option value="rejected" @selected($current === 'rejected')>Rejected</option>
                        <option value="expired" @selected($current === 'expired')>Expired</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-1">
                    <button class="btn btn-sm btn-primary flex-grow-1" type="submit">
                        <i class="fas fa-search me-1"></i>Cari
                    </button>
                    <a href="{{ route('admin.memberships.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="small">No</th>
                            <th class="small">Nama User</th>
                            <th class="small">Email</th>
                            <th class="small">Paket</th>
                            <th class="small">Durasi</th>
                            <th class="small">Harga</th>
                            <th class="small">Metode Pembayaran</th>
                            <th class="small">Tanggal Request</th>
                            <th class="small">Status</th>
                            <th class="small text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($memberships as $index => $m)
                            @php
                                $statusLabel = match($m->status) {
                                    'active' => 'Approved',
                                    'rejected' => 'Rejected',
                                    default => 'Pending',
                                };

                                // Expired label: treat as expired if users has end_date and is past.
                                $isExpired = false;
                                if ($m->status === 'active') {
                                    if ($m->user?->end_date) {
                                        $isExpired = 
                                            
                                            \Illuminate\Support\Carbon::parse($m->user->end_date)->lt(now());
                                    }
                                }

                                if ($isExpired) {
                                    $statusLabel = 'Expired';
                                }

                                $badge = match($statusLabel) {
                                    'Pending' => 'bg-warning text-dark',
                                    'Approved' => 'bg-success text-white',
                                    'Rejected' => 'bg-danger text-white',
                                    'Expired' => 'bg-secondary text-white',
                                    default => 'bg-secondary text-white'
                                };

                                $no = ($memberships->currentPage() - 1) * $memberships->perPage() + $index + 1;
                            @endphp

                            <tr>
                                <td class="small">{{ $no }}</td>
                                <td>
                                    <small class="fw-semibold">{{ $m->user?->name }}</small>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $m->user?->email }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info small">{{ \App\Models\MembershipUpgrade::planKey((int)$m->months) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary small">{{ $m->months }} bulan</span>
                                </td>
                                <td>
                                    <small class="fw-semibold">Rp{{ number_format((int)$m->amount, 0, ',', '.') }}</small>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $m->payment_method ?? '-' }}</small>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $m->requested_at?->format('d M Y') }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $badge }}">{{ $statusLabel }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column gap-2 align-items-center">
                                        <a href="{{ route('admin.memberships.show', $m) }}" class="btn btn-sm btn-outline-primary">
                                            Detail
                                        </a>

                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-success" 
                                                data-bs-toggle="modal" data-bs-target="#approveModal"
                                                data-id="{{ $m->id }}">
                                                Approve
                                            </button>

                                            <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" data-bs-target="#rejectModal"
                                                data-id="{{ $m->id }}">
                                                Reject
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                                    Tidak ada data membership.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($memberships?->hasPages())
                <div class="mt-4">
                    {{ $memberships->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header bg-success bg-opacity-10 border-success border-opacity-25">
                    <h5 class="modal-title fw-bold text-success">
                        <i class="fas fa-check me-2"></i>Konfirmasi Approve
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin mengaktifkan Membership Premium user ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="approveForm" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            Ya Approve
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header bg-danger bg-opacity-10 border-danger border-opacity-25">
                    <h5 class="modal-title fw-bold text-danger">
                        <i class="fas fa-times me-2"></i>Konfirmasi Reject
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menolak Membership ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="rejectForm" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            Ya Tolak
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const approveModal = document.getElementById('approveModal');
                const rejectModal = document.getElementById('rejectModal');
                const approveForm = document.getElementById('approveForm');
                const rejectForm = document.getElementById('rejectForm');

                if (!approveModal || !rejectModal || !approveForm || !rejectForm) return;

                approveModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const id = button?.getAttribute('data-id');
                    if (!id) return;
                    approveForm.setAttribute('action', `/admin/memberships/${id}/approve`);
                });

                rejectModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const id = button?.getAttribute('data-id');
                    if (!id) return;
                    rejectForm.setAttribute('action', `/admin/memberships/${id}/reject`);
                });
            })();
        </script>
    @endpush

@endsection

