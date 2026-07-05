@extends('layouts.admin')

@section('admin.content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="card-title mb-0">Borrowings</h5>
      <a href="{{ route('admin.borrowings.create') }}" class="btn btn-primary btn-sm">Tambah</a>
    </div>

    <form method="GET" action="{{ route('admin.borrowings.index') }}" class="row g-2 mb-3">
      <div class="col-md-4">
        <select name="status" class="form-select">
          <option value="" selected>Semua status</option>
          <option value="dipinjam" @selected(request('status')==='dipinjam')>dipinjam</option>
          <option value="dikembalikan" @selected(request('status')==='dikembalikan')>dikembalikan</option>
        </select>
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-outline-secondary w-100" type="submit">Filter</button>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Buku</th>
            <th>Pengguna</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($borrowings ?? [] as $b)
            <tr>
              <td>{{ $b->id }}</td>
              <td>{{ $b->book?->title }}</td>
              <td>{{ $b->user?->name }}</td>
              <td>{{ $b->borrow_date?->format('Y-m-d') }}</td>
              <td>{{ $b->return_date?->format('Y-m-d') }}</td>
              <td>{{ $b->status }}</td>
              <td>
                <a href="{{ route('admin.borrowings.edit', $b) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                <form action="{{ route('admin.borrowings.destroy', $b) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus borrowing ini?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center text-muted">Tidak ada data</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @isset($borrowings)
      {{ $borrowings->links() }}
    @endisset
  </div>
</div>
@endsection

