@extends('layouts.admin')

@section('admin.content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title mb-3">Tambah Book</h5>

    <form method="POST" action="{{ route('admin.books.store') }}" class="row g-3" enctype="multipart/form-data">
      @csrf

      <div class="col-md-6">
        <label class="form-label">Kategori</label>
        <input name="category_name" value="{{ old('category_name') }}" class="form-control @error('category_name') is-invalid @enderror" placeholder="Contoh: SAINS">
        @error('category_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Judul</label>
        <input name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror">
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Penulis</label>
        <input name="author" value="{{ old('author') }}" class="form-control @error('author') is-invalid @enderror">
        @error('author')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Penerbit</label>
        <input name="publisher" value="{{ old('publisher') }}" class="form-control @error('publisher') is-invalid @enderror">
        @error('publisher')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-4">
        <label class="form-label">Tahun Terbit</label>
        <input type="number" name="publication_year" value="{{ old('publication_year') }}" class="form-control @error('publication_year') is-invalid @enderror">
        @error('publication_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-8">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-4">
        <label class="form-label">Cover Image</label>
        <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror">
        @error('cover_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-4">
        <label class="form-label">File PDF</label>
        <input type="file" name="file_pdf" class="form-control @error('file_pdf') is-invalid @enderror">
        @error('file_pdf')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-4">
        <label class="form-label">Stok</label>
        <input type="number" name="stock" value="{{ old('stock') }}" class="form-control @error('stock') is-invalid @enderror">
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


