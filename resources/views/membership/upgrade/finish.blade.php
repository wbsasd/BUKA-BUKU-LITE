@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="card shadow-sm border-0 rounded-3">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Selesai</h5>
        <div class="small text-muted">Langkah 3 dari 3</div>
      </div>

      <div class="row g-4 align-items-start">
        <div class="col-12 col-lg-7">
          <div class="card border-0 shadow-sm p-3">
            <h6 class="mb-2">Status Request</h6>
            <div class="d-flex flex-column gap-2">
              <div class="d-flex justify-content-between"><span class="text-muted">Pembayaran</span><strong>{{ $upgrade->payment_status }}</strong></div>
              <div class="d-flex justify-content-between"><span class="text-muted">Status Admin</span><strong>{{ $upgrade->status }}</strong></div>
              <div class="d-flex justify-content-between"><span class="text-muted">Paket</span><strong>{{ $upgrade->months }} bulan</strong></div>
              <div class="d-flex justify-content-between"><span class="text-muted">Total</span><strong>Rp{{ number_format($upgrade->amount,0,',','.') }}</strong></div>
            </div>

            <div class="alert alert-warning mt-3 mb-0">
              Premium baru aktif setelah Admin menyetujui request Anda.
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-5">
          <div class="card border-0 shadow-sm p-3">
            <h6 class="mb-2">Langkah Berikutnya</h6>
            <ol class="mb-0 text-muted small">
              <li>Admin akan memeriksa request membership Anda.</li>
              <li>Jika disetujui, status membership Anda menjadi <strong>active</strong>.</li>
              <li>Setelah itu Anda bisa menggunakan fitur membership (mis. booking/pinjam).</li>
            </ol>
          </div>
        </div>

      </div>

      <div class="d-flex justify-content-end mt-4">
        <a href="{{ route('dashboard') }}" class="btn btn-primary">Kembali ke Dashboard</a>
      </div>
    </div>
  </div>
</div>
@endsection

