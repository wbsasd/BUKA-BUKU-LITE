@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4 class="mb-0">Upgrade Membership</h4>
      <div class="text-muted small">Pilih paket durasi Anda</div>
    </div>
    <div class="small text-muted">Langkah 1 dari 3</div>
  </div>

  <div class="row g-3">
    @php
      $plans = [
        ['months'=>3, 'amount'=>49000, 'badge'=>null, 'badgeClass'=>''],
        ['months'=>6, 'amount'=>89000, 'badge'=>'Paling Hemat', 'badgeClass'=>'bg-success'],
        ['months'=>12, 'amount'=>149000, 'badge'=>'Best Value', 'badgeClass'=>'bg-primary'],
      ];
    @endphp

    @foreach($plans as $p)
      <div class="col-12 col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0" style="border-top:4px solid #0d6efd;">
          <div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <div class="text-muted">Paket</div>
                <h5 class="mb-1">{{ $p['months'] }} bulan</h5>
              </div>
              @if($p['badge'])
                <span class="badge {{ $p['badgeClass'] }}">{{ $p['badge'] }}</span>
              @endif
            </div>

            <div class="mt-3">
              <div class="display-6 fw-bold">Rp{{ number_format($p['amount'],0,',','.') }}</div>
              <div class="text-muted small">Akses premium sesuai durasi</div>
            </div>

            <div class="mt-3">
              <ul class="list-unstyled mb-0">
                <li class="d-flex gap-2 align-items-center mb-2"><i class="bi bi-check2-circle text-success"></i> Akses e-book penuh</li>
                <li class="d-flex gap-2 align-items-center mb-2"><i class="bi bi-check2-circle text-success"></i> Pinjam lebih banyak</li>
                <li class="d-flex gap-2 align-items-center"><i class="bi bi-check2-circle text-success"></i> Approval Admin</li>
              </ul>
            </div>

            <div class="mt-auto pt-3">
              <form method="POST" action="{{ route('membership.upgrade.review') }}">
                @csrf
                <input type="hidden" name="months" value="{{ $p['months'] }}" />
                <button class="btn btn-primary w-100">Pilih Paket</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="alert alert-info mt-4 mb-0">
    Premium akan aktif setelah Admin menyetujui request Anda.
  </div>
</div>
@endsection

