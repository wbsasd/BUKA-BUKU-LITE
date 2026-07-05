@extends('layouts.admin')

@section('admin.content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="card-title mb-0">Detail User</h5>
      <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>

    <div class="row g-3">
      <div class="col-md-6">
        <div class="text-muted small">Nama</div>
        <div class="fw-semibold">{{ $user->name }}</div>
      </div>
      <div class="col-md-6">
        <div class="text-muted small">Email</div>
        <div class="fw-semibold">{{ $user->email }}</div>
      </div>
      <div class="col-md-6">
        <div class="text-muted small">Role</div>
        <div class="fw-semibold">{{ $user->role }}</div>
      </div>
      <div class="col-md-6">
        <div class="text-muted small">Status Akun</div>
        <div class="fw-semibold">{{ $user->status ?? 'active' }}</div>
      </div>
      <div class="col-md-6">
        <div class="text-muted small">Status Membership</div>
        <div class="fw-semibold">{{ $user->membership ?? '-' }}</div>
      </div>
      <div class="col-md-6">
        <div class="text-muted small">Tanggal Bergabung (Created At)</div>
        <div class="fw-semibold">{{ optional($user->created_at)->format('Y-m-d H:i') }}</div>
      </div>
      <div class="col-md-6">
        <div class="text-muted small">Terakhir Login</div>
        <div class="fw-semibold">{{ $user->last_login_at ? optional($user->last_login_at)->format('Y-m-d H:i') : '-' }}</div>
      </div>
      <div class="col-12">
        <div class="text-muted small mb-1">Riwayat Membership</div>
        <div>
          {{ $user->membership_history ?? '-' }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

