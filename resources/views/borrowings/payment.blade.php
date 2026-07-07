@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="card shadow-sm rounded-3">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Pembayaran</h5>
        <div class="small text-muted">Langkah 2 dari 3</div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="card border-0 shadow-sm p-3 mb-3">
            <h6 class="mb-1">Ringkasan</h6>
            <div class="small text-muted">{{ $borrowing->book?->title }}</div>
            <div class="mt-2">Durasi: <strong>{{ $borrowing->duration }} hari</strong></div>
            <div>Harga: <strong>Rp{{ number_format($borrowing->price,0,',','.') }}</strong></div>
          </div>

          <form method="POST" action="{{ route('borrow.pay', $borrowing) }}">
            @csrf
            <div class="mb-3">
              <label class="form-label">Pilih Metode Pembayaran (Dummy)</label>
              <select name="payment_method" class="form-select" required>
                <option value="transfer">Transfer Bank</option>
                <option value="gopay">GoPay</option>
                <option value="ovo">OVO</option>
                <option value="cash">Cash</option>
              </select>
            </div>

            <div class="d-flex justify-content-end">
              <a href="{{ route('borrow.booking', $borrowing->book) }}" class="btn btn-outline-secondary me-2">Kembali</a>
              <button class="btn btn-success">Bayar Sekarang</button>
            </div>
          </form>
        </div>

        <div class="col-md-6">
          <div class="card border-0 shadow-sm p-3">
            <h6>Petunjuk</h6>
            <p class="text-muted small mb-0">Pembayaran bersifat dummy — klik "Bayar Sekarang" untuk menyelesaikan proses.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
