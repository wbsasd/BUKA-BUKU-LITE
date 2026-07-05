@extends('layouts.admin')

@section('admin.content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="card-title mb-0">Books</h5>
      <a href="{{ route('admin.books.create') }}" class="btn btn-primary btn-sm">Tambah</a>
    </div>

    <div class="row g-2 mb-3">
      <div class="col-md-4">
        <input type="text" name="q" form="books-filter" class="form-control" placeholder="Cari judul/penulis/penerbit">
      </div>
      <div class="col-md-4">
        <select name="category_id" form="books-filter" class="form-select">
          <option value="">Semua kategori</option>
          @isset($categories)
            @foreach($categories as $c)
              <option value="{{ $c->id }}" @selected(request('category_id') == $c->id)>{{ $c->name }}</option>
            @endforeach
          @endisset
        </select>
      </div>
      <div class="col-md-4 d-flex">
        <form id="books-filter" method="GET" action="{{ route('admin.books.index') }}" class="d-flex gap-2 w-100">
          <div class="d-none">
            <input type="hidden" name="q" value="{{ request('q') }}">
          </div>
          <button class="btn btn-outline-secondary w-100" type="submit">Filter</button>
        </form>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead>
          <tr>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Penerbit</th>
            <th>Tahun</th>
            <th>Stok</th>
            <th>Kategori</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($books ?? [] as $book)
            <tr>
              <td>{{ $book->title }}</td>
              <td>{{ $book->author }}</td>
              <td>{{ $book->publisher }}</td>
              <td>{{ $book->publication_year }}</td>
              <td>{{ $book->stock }}</td>
              <td>{{ $book->category?->name }}</td>
              <td>
                <a href="{{ route('admin.books.show', $book) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-sm btn-outline-primary ms-1">Edit</a>
                <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus book ini?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger ms-1" type="submit">Hapus</button>
                </form>
              </td>

            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center text-muted">Tidak ada data</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @isset($books)
      {{ $books->links() }}
    @endisset
  </div>
</div>
@endsection

