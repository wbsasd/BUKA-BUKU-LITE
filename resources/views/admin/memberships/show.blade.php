@extends('layouts.admin')

@section('admin.content')

    @php
        $user = $membership->user;
        $planKey = \App\Models\MembershipUpgrade::planKey((int) $membership->months);

        $statusLabel = match($membership->status) {
            'active' => 'Approved',
            'rejected' => 'Rejected',
            default => 'Pending'
        };

        // Expired detection if users has end_date
        $isExpired = false;
        if ($membership->status === 'active' && $user?->end_date) {
            $isExpired = \Illuminate\Support\Carbon::parse($user->end_date)->lt(now());
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

        $paymentStatus = $membership->payment_status ?? 'unpaid';
    @endphp

    <div class="mb-4">
        <a href="{{ route('admin.memberships.index') }}" class="btn btn-sm btn-outline-secondary">&larr; Kembali</a>
        <div class="mt-3">
            <h3 class="fw-bold mb-0">Detail Membership</h3>
            <p class="text-muted small mb-0">Request #{{ $membership->id }}</p>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Informasi User</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small">Nama User</div>
                            <div class="fw-semibold">{{ $user?->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Email</div>
                            <div class="fw-semibold">{{ $user?->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Role User</div>
                            <div class="fw-semibold">{{ ucfirst($user?->role ?? 'pengguna') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Status</div>
                            <div>
                                <span class="badge {{ $badge }}">{{ $statusLabel }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Informasi Membership</h5>

                    <div class="row g-3">
                        <div class="col-12">
                            <div class="text-muted small">Membership</div>
                            <div class="fw-semibold">Membership Premium ({{ $planKey }})</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Durasi</div>
                            <div class="fw-semibold">{{ $membership->months }} bulan</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Harga</div>
                            <div class="fw-semibold">Rp{{ number_format((int)$membership->amount, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-12">
                            <div class="text-muted small">Metode Pembayaran</div>
                            <div class="fw-semibold">{{ $membership->payment_method ?? '-' }}</div>
                        </div>
                        <div class="col-12">
                            <div class="text-muted small">Payment Status</div>
                            <div class="fw-semibold">{{ ucfirst($paymentStatus) }}</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Tanggal</h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="text-muted small">Tanggal Request</div>
                            <div class="fw-semibold">{{ $membership->requested_at?->format('d M Y H:i') }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Tanggal Approve</div>
                            <div class="fw-semibold">{{ $membership->approved_at?->format('d M Y H:i') ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Tanggal Expired</div>
                            <div class="fw-semibold">
                                {{ $user?->end_date ? \Illuminate\Support\Carbon::parse($user->end_date)->format('d M Y') : '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

