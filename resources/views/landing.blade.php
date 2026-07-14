@extends('layouts.app')
<!-- PEMBUNGKUS UTAMA: Menggunakan w-100 agar selebar layar, position-relative, dan tinggi minimal (min-vh-50 atau disesuaikan) -->
<div class="position-relative w-100 overflow-hidden" style="
     background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.pexels.com/photos/16689056/pexels-photo-16689056.jpeg'); 
     background-size: cover; 
     background-position: center; 
     min-height: 500px; /* Atur tinggi hero section di sini */
">

    <!-- KODE ANDA MULAI DARI SINI (Sudah disesuaikan sedikit agar pas di tengah) -->
    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-7">
                    <div class="p-3 p-md-5">
                        <h1 class="fw-bold text-white mb-3" style="font-size: clamp(44px, 4.6vw, 56px); line-height: 1.1;">
                            Selamat datang
                            <br>
                            di Buka Buku Lite
                        </h1>
                        <div aria-hidden="true" class="mb-3" style="width: 44px; height: 3px; background: #0d6efd; border-radius: 2px;"></div>
                        <p class="mb-4" style="color: rgba(255,255,255,.72); font-size: 1.05rem; line-height: 1.7; max-width: 380px;">
                            Temukan, baca, dan pinjam buku dengan mudah.
                        </p>

                        <div class="mb-3" style="max-width: 520px;">
                            <form class="d-flex" role="search" method="GET" action="http://127.0.0.1:8000">
                                <div class="input-group">
                                    <input type="search" name="q" value="" class="form-control" placeholder="Cari judul, penulis, atau kategori..." aria-label="Search">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-primary me-2" onclick="window.location='http://127.0.0.1:8000/login'">
                                Masuk
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('content')
<div class="container">
  <!-- {{-- Hero (Full Banner) --}}
  <section class="pt-0">
    <div class="pb-4">
      <div
        class="position-relative rounded-4 overflow-hidden shadow-sm"
        style="height: clamp(500px, 55vh, 550px); background-image: url('https://picsum.photos/seed/library/1200/800'); background-size: cover; background-position: center; background-repeat: no-repeat;"
      >
        {{-- overlay gradient --}}
        <div
          aria-hidden="true"
          class="position-absolute top-0 start-0 w-100 h-100"
          style="background: linear-gradient(to right, rgba(0,0,0,.75), rgba(0,0,0,.25), rgba(0,0,0,.05));"
        ></div>

        {{-- content --}}
        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
          <div class="container">
            <div class="row">
              <div class="col-12 col-lg-7">
                <div class="p-3 p-md-5">
                  <h1 class="fw-bold text-white mb-3" style="font-size: clamp(44px, 4.6vw, 56px); line-height: 1.1;">
                    Selamat datang
                    <br>
                    di Buka Buku Lite
                  </h1>
                  <div aria-hidden="true" class="mb-3" style="width: 44px; height: 3px; background: #0d6efd; border-radius: 2px;"></div>
                  <p class="mb-4" style="color: rgba(255,255,255,.72); font-size: 1.05rem; line-height: 1.7; max-width: 380px;">
                    Temukan, baca, dan pinjam buku dengan mudah.
                  </p>

                  <div class="mb-3" style="max-width: 520px;">
                    <x-search-bar placeholder="Cari judul, penulis, atau kategori..." name="q" />
                  </div>


                  <div class="d-flex gap-2 flex-wrap">
                    <x-button
                      variant="primary"
                      class="me-2"
                      onclick="window.location='{{ route('login') }}'"
                    >
                      Masuk
                    </x-button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section> -->

  {{-- Kategori --}}
  <section class="py-3">
    <h5 class="mb-3">Kategori</h5>
    <div class="d-flex gap-2 flex-wrap">
      @foreach($categories as $category)
        <a href="#" class="btn btn-outline-primary btn-sm">{{ $category->name }}</a>
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
      @if($books->isEmpty())
        <div class="col-12 text-muted">Tidak ada buku.</div>
      @else
        @foreach($books->take(4) as $book)
          <div class="col-6 col-md-3">
            <x-book-card
              :bookId="$book->id"
              title="{{ $book->title }}"
              author="{{ $book->author }}"
              cover="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('images/placeholder-cover.png') }}"
              rating="4"
            >
              <div class="small text-muted">{{ $book->category?->name }}</div>
              <div class="mt-2 d-grid">
                <a href="{{ route('book.detail', ['id' => $book->id]) }}" class="btn btn-sm btn-primary">Pinjam</a>
              </div>
            </x-book-card>
          </div>
        @endforeach
      @endif
    </div>
  </section>

  {{-- Popular Books --}}
  <section class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Populer</h5>
      <a href="#" class="small">Lihat semua</a>
    </div>
    <div class="row g-3">
      @if($books->skip(4)->isEmpty())
        <div class="col-12 text-muted">Tidak ada buku.</div>
      @else
        @foreach($books->skip(4)->take(4) as $book)
          <div class="col-6 col-md-3">
            <x-book-card
              :bookId="$book->id"
              title="{{ $book->title }}"
              author="{{ $book->author }}"
              cover="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('images/placeholder-cover.png') }}"
              rating="5"
            >
              <div class="small text-muted">{{ $book->category?->name }}</div>
              <div class="mt-2 d-grid">
                <a href="{{ route('book.detail', ['id' => $book->id]) }}" class="btn btn-sm btn-outline-primary">Pinjam</a>
              </div>
            </x-book-card>
          </div>
        @endforeach
      @endif
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
          @auth
            <x-button variant="light" onclick="window.location='{{ route('membership.upgrade.plans') }}'">Upgrade Sekarang</x-button>
          @else
            <x-button variant="light" onclick="window.location='{{ route('login') }}'">Upgrade Sekarang</x-button>
          @endauth
        </div>

      </div>
    </div>
  </section>

</div>
@endsection
