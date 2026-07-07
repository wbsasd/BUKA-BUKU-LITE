@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="card shadow-sm rounded-3 text-center p-4">
    <div class="card-body">
      <div class="mb-3">
        <i class="bi bi-check-circle-fill text-success" style="font-size:48px"></i>
      </div>
      <h4>Transaksi Berhasil</h4>
      <p class="text-muted">Peminjaman Anda telah tercatat. Silakan cek riwayat peminjaman untuk detail.</p>
      <div class="d-flex justify-content-center gap-2 mt-3">
        <a href="{{ route('borrow.history') }}" class="btn btn-primary">Lihat Riwayat</a>
        <a href="{{ route('book.detail', $borrowing->book) }}" class="btn btn-outline-secondary">Kembali ke Buku</a>
      </div>
    </div>
  </div>
</div>
@endsection
