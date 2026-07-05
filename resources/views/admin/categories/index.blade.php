@extends('layouts.admin')

@section('admin.content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="card-title mb-0">Categories</h5>
      <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">Tambah</a>
    </div>

    <form method="GET" action="{{ route('admin.categories.index') }}" class="row g-2 mb-3">
      <div class="col-md-6">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari kategori">
      </div>
      <div class="col-md-3">
        <button class="btn btn-outline-secondary w-100">Filter</button>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories ?? [] as $category)
            <tr>
              <td>{{ $category->name }}</td>
              <td>
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="2" class="text-center text-muted">Tidak ada data</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @isset($categories)
      {{ $categories->links() }}
    @endisset
  </div>
</div>
@endsection

