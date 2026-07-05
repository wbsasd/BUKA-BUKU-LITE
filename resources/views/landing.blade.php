@extends('layouts.app')

@section('content')
<div class="container">
  {{-- Hero --}}
  <section class="py-5">
    <div class="row align-items-center">
      <div class="col-md-7">
        <h1 class="display-6 fw-bold">Selamat datang di Buka Buku Lite</h1>
        <p class="lead text-muted">Temukan buku favoritmu, baca cuplikan, dan pinjam secara mudah.</p>
        <div class="mb-3">
          <x-search-bar placeholder="Cari judul, penulis, atau kategori..." name="q" />
        </div>
        <div>
          <x-button variant="primary" class="me-2" onclick="window.location='{{ route('login') }}'">Masuk</x-button>
        </div>
      </div>
      <div class="col-md-5 d-none d-md-block">
        <img src="https://picsum.photos/seed/library/600/400" alt="Hero" class="img-fluid rounded shadow-sm">
      </div>
    </div>
  </section>

  {{-- Kategori --}}
  <section class="py-3">
    <h5 class="mb-3">Kategori</h5>
    <div class="d-flex gap-2 flex-wrap">
      @foreach(['Fiksi','Non-Fiksi','Sejarah','Teknologi','Anak','Sains'] as $cat)
        <a href="#" class="btn btn-outline-primary btn-sm">{{ $cat }}</a>
      @endforeach
    </div>
  </section>

  {{-- Featured Books --}}
  <section class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Buku Unggulan</h5>
      <a href="#" class="small">Lihat semua</a>
    </div>

    <div class="row g-3">
      @for($i=1;$i<=4;$i++)
        <div class="col-6 col-md-3">
          <x-book-card title="Judul Buku {{$i}}" author="Pengarang {{$i}}" cover="https://picsum.photos/seed/book{{$i}}/300/420" rating="4">
            <div class="mt-2 d-grid">
              <a href="#" class="btn btn-sm btn-primary">Baca</a>
            </div>
          </x-book-card>
        </div>
      @endfor
    </div>
  </section>

  {{-- Popular Books --}}
  <section class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Populer</h5>
      <a href="#" class="small">Lihat semua</a>
    </div>
    <div class="row g-3">
      @for($i=5;$i<=8;$i++)
        <div class="col-6 col-md-3">
          <x-book-card title="Buku Populer {{$i}}" author="Penulis {{$i}}" cover="https://picsum.photos/seed/pop{{$i}}/300/420" rating="5">
            <div class="mt-2 d-grid">
              <a href="#" class="btn btn-sm btn-outline-primary">Detail</a>
            </div>
          </x-book-card>
        </div>
      @endfor
    </div>
  </section>

  {{-- Membership Banner --}}
  <section class="py-4">
    <div class="card bg-primary text-white overflow-hidden">
      <div class="row g-0 align-items-center">
        <div class="col-md-8 p-4">
          <h4 class="fw-bold">Jadi Anggota Premium</h4>
          <p class="mb-0">Akses baca penuh e-book tanpa batas dan pinjam lebih banyak buku.</p>
        </div>
        <div class="col-md-4 p-4 text-md-end">
          <x-button variant="light">Upgrade Sekarang</x-button>
        </div>
      </div>
    </div>
  </section>

</div>
@endsection
