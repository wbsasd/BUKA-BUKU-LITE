@extends('layouts.admin')

@section('admin.content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title mb-3">Edit Book</h5>

    <form method="POST" action="{{ route('admin.books.update', $book) }}" class="row g-3" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="col-md-6">
        <label class="form-label">Kategori</label>
        <input name="category_name" value="{{ old('category_name', optional($book->category)->name) }}" class="form-control @error('category_name') is-invalid @enderror" placeholder="Contoh: NOVEL">
        @error('category_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Judul</label>
        <input name="title" value="{{ old('title', $book->title) }}" class="form-control @error('title') is-invalid @enderror">
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Penulis</label>
        <input name="author" value="{{ old('author', $book->author) }}" class="form-control @error('author') is-invalid @enderror">
        @error('author')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Penerbit</label>
        <input name="publisher" value="{{ old('publisher', $book->publisher) }}" class="form-control @error('publisher') is-invalid @enderror">
        @error('publisher')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-4">
        <label class="form-label">Tahun Terbit</label>
        <input type="number" name="publication_year" value="{{ old('publication_year', $book->publication_year) }}" class="form-control @error('publication_year') is-invalid @enderror">
        @error('publication_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-8">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $book->description) }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-4">
        <label class="form-label">Cover Image (opsional)</label>
        <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror">
        @if(!empty($book->cover_image))
          <div class="small text-muted mt-2">Saat ini: {{ $book->cover_image }}</div>
        @endif
        @error('cover_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-4">
        <label class="form-label">File PDF (opsional)</label>
        <input type="file" name="file_pdf" class="form-control @error('file_pdf') is-invalid @enderror">
        @if(!empty($book->file_pdf))
          <div class="small text-muted mt-2">Saat ini: {{ $book->file_pdf }}</div>
        @endif
        @error('file_pdf')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-4">
        <label class="form-label">Stok</label>
        <input type="number" name="stock" value="{{ old('stock', $book->stock) }}" class="form-control @error('stock') is-invalid @enderror">
        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-12 d-flex gap-2">
        <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection


