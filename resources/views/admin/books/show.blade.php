@extends('layouts.admin')

@section('admin.content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="card-title mb-0">Detail Buku</h5>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-sm btn-outline-primary">Edit</a>
        <a href="{{ route('admin.books.index') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-12 col-lg-4">
        <div class="card">
          <div class="card-body">
            <h6 class="mb-3">Cover</h6>

            @if(!empty($book->cover_image))
              <div class="text-muted small mb-2">
                Cover tersimpan di storage (private).
              </div>
              <div class="border rounded p-3 bg-light">
                <div class="fw-semibold">{{ $book->cover_image }}</div>
              </div>
            @else
              <div class="text-muted">Belum ada cover</div>
            @endif
          </div>

        </div>
      </div>

      <div class="col-12 col-lg-8">
        <h3 class="mb-2">{{ $book->title }}</h3>
        <p class="text-muted mb-3">
          {{ $book->author }} · {{ $book->publisher }} · {{ $book->publication_year }}
        </p>

        <div class="mb-3">
          <span class="badge bg-secondary">Kategori: {{ $book->category?->name }}</span>
        </div>

        <section class="mb-4">
          <h6>Deskripsi</h6>
          <div class="text-muted" style="white-space: pre-wrap">{{ $book->description }}</div>
        </section>

        <div class="row g-3">
          <div class="col-12 col-md-6">
            <div class="p-3 border rounded">
              <div class="small text-muted">Stok</div>
              <div class="fs-5 fw-semibold">{{ $book->stock }}</div>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="p-3 border rounded">
              <div class="small text-muted">File PDF</div>
              <div class="fs-6 fw-semibold">{{ !empty($book->file_pdf) ? 'Tersedia' : 'Belum ada' }}</div>
              @if(!empty($book->file_pdf))
                <div class="small text-muted mt-2">Path: {{ $book->file_pdf }}</div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

