@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="card shadow-sm rounded-3">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Riwayat Peminjaman</h5>
      </div>

      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>Buku</th>
              <th>Durasi</th>
              <th>Harga</th>
              <th>Tanggal Pinjam</th>
              <th>Batas Kembali</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($borrowings as $b)
              <tr>
                <td>{{ $b->book?->title }}</td>
                <td>{{ $b->duration }} hari</td>
                <td>Rp{{ number_format($b->price,0,',','.') }}</td>
                <td>{{ $b->borrowed_at?->format('Y-m-d H:i') }}</td>
                <td>{{ $b->due_date?->format('Y-m-d') }}</td>
                <td>{{ $b->status }}</td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted">Belum ada peminjaman</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{ $borrowings->links() }}
    </div>
  </div>
</div>
@endsection
