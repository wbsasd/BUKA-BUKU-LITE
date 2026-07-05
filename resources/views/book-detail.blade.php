@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="row g-4">
    <div class="col-12 col-lg-4">
      <div class="card">
        <img src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('images/placeholder-cover.png') }}" alt="{{ $book->title }}">
        <div class="card-body">
          <h5 class="card-title">{{ $book->title }}</h5>
          <p class="text-muted mb-1">{{ $book->author }}</p>
          <div class="mb-2">
            <span class="badge bg-secondary">Kategori: {{ $book->category?->name }}</span>
          </div>
          <div class="mb-2 text-warning">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-half"></i>
            <span class="small text-muted ms-2">4.5 (200 ulasan)</span>
          </div>
          <div class="d-grid gap-2">
            <x-button variant="primary">Pinjam Buku</x-button>
            <a href="{{ $book->stock > 0 ? route('reader', ['id' => $book->id]) : '#' }}" class="btn btn-outline-primary">Baca Sekarang</a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-8">
      <h3 class="mb-3">{{ $book->title }}</h3>
      <p class="text-muted">{{ $book->author }} · {{ $book->publisher }} · {{ $book->publication_year }}</p>
      <p class="small text-muted mb-2">Stock: <strong>{{ $book->stock }}</strong></p>

      <section class="mb-4">
        <h6>Kategori</h6>
        <div class="d-flex gap-2 flex-wrap">
          <span class="badge bg-outline-primary border"Sekarang saya ingin menghilangkan semua data dummy pada halaman Landing Page (Guest).

Saat ini:
- Dashboard user sudah menggunakan database books.
- Landing page guest masih memakai data hardcode.

Saya ingin:

1. Landing page mengambil data dari tabel books.

2. Menampilkan:
- cover
- judul
- author
- kategori

3. Menggunakan relasi category.

4. Mengambil 8 buku terbaru.

5. Mengambil seluruh kategori dari database.

6. Jika database kosong tampilkan:
"Tidak ada buku."

7. Jangan mengubah layout.
Jangan mengubah CSS.

Hanya mengganti data dummy menjadi data database.

Gunakan Controller, bukan query langsung di Blade.>{{ $book->category?->name }}</span>
        </div>
      </section>
    </div>

    <div class="col-12">
      @if(isset($book->description) && $book->description)
      <section class="mb-4">
        <h5>Sinopsis</h5>
        <p class="text-justify">{{ $book->description }}</p>
      </section>
      @endif
    </div>

    <div class="col-12">
      <section>
        <h6>Rating & Ulasan</h6>
        <div class="d-flex align-items-center mb-2">
          <div class="me-3 display-6 text-warning">4.5</div>
          <div>
            <div class="text-warning">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-half"></i>
            </div>
            <div class="small text-muted">200 penilaian</div>
          </div>
        </div>
        <div class="list-group">
          @for($i=1;$i<=3;$i++)
            <div class="list-group-item">
              <div class="fw-semibold">Pengguna {{$i}}</div>
              <div class="small text-muted">Ulasan singkat tentang buku ini. Lorem ipsum dolor sit amet.</div>
            </div>
          @endfor
        </div>
      </section>
    </div>
  </div>
</div>
@endsection
