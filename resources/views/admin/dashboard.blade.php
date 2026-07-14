@extends('layouts.admin')

@section('content')

  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
      <h4 class="card-title">Hello, {{ auth()->user()->name }}</h4>
      <p class="text-muted mb-0">Selamat datang di panel administrasi.</p>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <p class="text-muted small mb-2">⏳ Membership Pending</p>
          <h3 class="fw-bold mb-0 {{ ($membershipPendingCount ?? 0) > 0 ? 'text-danger' : '' }}">{{ $membershipPendingCount ?? 0 }}</h3>
          @if(($membershipPendingCount ?? 0) > 0)
            <span class="badge bg-danger mt-2">Perlu ditinjau</span>
          @endif
        </div>
      </div>
    </div>
  </div>

@endsection



