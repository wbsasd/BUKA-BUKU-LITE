@extends('layouts.admin')

@section('admin.content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="card-title mb-0">Settings</h5>
      <a href="{{ route('admin.settings.create') }}" class="btn btn-primary btn-sm">Tambah</a>
    </div>

    <div class="alert alert-info">
      Halaman Settings tersedia. Karena belum ada model/table setting di repo, data belum bisa ditampilkan.
    </div>

    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Key</th>
          <th>Value</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($settings ?? [] as $s)
          <tr>
            <td>{{ $s->key }}</td>
            <td>{{ $s->value }}</td>
            <td>
              <a href="{{ route('admin.settings.edit', $s) }}" class="btn btn-sm btn-outline-primary">Edit</a>
            </td>
          </tr>
        @empty
          <tr><td colspan="3" class="text-center text-muted">Tidak ada data</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection

