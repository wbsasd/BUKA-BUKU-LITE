@extends('layouts.admin')

@section('admin.content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="card-title mb-0">Membership</h5>
      <a href="{{ route('admin.memberships.create') }}" class="btn btn-primary btn-sm">Tambah</a>
    </div>

    <div class="alert alert-info">
      Halaman Membership tersedia. Karena belum ada model/table membership di repo, data belum bisa ditampilkan.
    </div>

    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Nama</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($memberships ?? [] as $m)
          <tr>
            <td>{{ $m->name }}</td>
            <td>
              <a href="{{ route('admin.memberships.edit', $m) }}" class="btn btn-sm btn-outline-primary">Edit</a>
            </td>
          </tr>
        @empty
          <tr><td colspan="2" class="text-center text-muted">Tidak ada data</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection

