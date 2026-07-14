@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="card shadow-sm rounded-3 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Dummy Payment</h5>
        <div class="small text-muted">Langkah 2 dari 3</div>
      </div>

      <div class="row g-4">
        <div class="col-12 col-lg-6">
          <div class="card border-0 shadow-sm p-3 mb-3 bg-white">
            <h6 class="mb-1">Ringkasan</h6>
            <div class="small text-muted">Paket: <strong>{{ $upgrade->months }} bulan</strong></div>
            <div class="mt-2">Total: <strong>Rp{{ number_format($upgrade->amount,0,',','.') }}</strong></div>

            <form method="POST" action="{{ route('membership.upgrade.pay', $upgrade) }}">
              @csrf

              <div class="mt-3 mb-3">
                <label class="form-label">Pilih Metode Pembayaran (Dummy)</label>
                <select name="payment_method" class="form-select" required>
                  <option value="transfer">Transfer Bank</option>
                  <option value="gopay">GoPay</option>
                  <option value="ovo">OVO</option>
                  <option value="cash">Cash</option>
                </select>
              </div>

              <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('membership.upgrade.plans') }}" class="btn btn-outline-secondary">Kembali</a>
                <button class="btn btn-success">Bayar Sekarang</button>
              </div>
            </form>
          </div>
        </div>

        <div class="col-12 col-lg-6">
          <div class="card border-0 shadow-sm p-3 bg-white h-100">
            <h6>Petunjuk</h6>
            <p class="text-muted small mb-2">Pembayaran bersifat dummy — klik "Bayar Sekarang" untuk menyelesaikan proses.</p>
            <div class="alert alert-warning mb-0">
              Setelah pembayaran dummy berhasil, request akan masuk status pending untuk approval Admin.
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection

