@extends('layouts.app')

@section('content')
<div class="container">
  {{-- Hero --}}
  <section class="py-4">
    <div class="bb-hero rounded-4 overflow-hidden position-relative">
      <div class="bb-hero-bg" aria-hidden="true"></div>

      <div class="position-relative">
        <div class="row align-items-center g-4">
          <div class="col-12 col-lg-7">
            <div class="p-2 p-md-3">
              <h1 class="fw-bold text-white mb-3" style="font-size: clamp(44px, 4.6vw, 56px); line-height: 1.1;">
                Selamat datang<br>di Buka Buku Lite
              </h1>

              <div aria-hidden="true" class="mb-3" style="width: 44px; height: 3px; background: #0d6efd; border-radius: 2px;"></div>

              <p class="mb-4" style="color: rgba(255,255,255,.72); font-size: 1.05rem; line-height: 1.7; max-width: 480px;">
                Temukan, baca, dan pinjam buku dengan mudah.
              </p>

              <div class="mb-3" style="max-width: 520px;">
                <x-search-bar placeholder="Cari judul, penulis, atau kategori..." name="q" />
              </div>

              <div class="d-flex gap-2 flex-wrap align-items-center">
                @guest
                  <x-button variant="primary" class="bb-rounded-12 bb-hover-lift" onclick="window.location='{{ route('login') }}'">
                    Masuk
                  </x-button>
                @else
                  @php
                    $roleName = strtolower(Auth::user()->role ?? 'user');
                    // URL berdasarkan role (tanpa last read / reader fallback).
                    $basicUpgradeUrl = route('membership.upgrade.plans');
                    $continueUrl = route('dashboard');
                  @endphp

                  @if($roleName === 'pengguna')
                    <x-button variant="primary" class="bb-rounded-12 bb-hover-lift" onclick="window.location='{{ $basicUpgradeUrl }}'">
                      ⭐ Upgrade Sekarang
                    </x-button>
                  @elseif($roleName === 'premium')
                    <x-button variant="primary" class="bb-rounded-12 bb-hover-lift" onclick="window.location='{{ $continueUrl }}'">
                      📖 Lanjut Membaca
                    </x-button>
                  @endif
                @endguest
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-5">
            <div class="position-relative" aria-hidden="true">
              <div class="bb-hero-illustration mx-auto">
                <div class="bb-hero-blob"></div>
                <div class="bb-hero-badge">
                  <i class="bi bi-book-half"></i>
                </div>
              </div>

              <div class="mt-3 d-flex justify-content-center">
                <div class="bb-card" style="background: rgba(255,255,255,0.12); border-color: rgba(255,255,255,0.18);">
                  <div class="p-3">
                    <div class="fw-bold text-white">Digital Library</div>
                    <div class="small" style="color: rgba(255,255,255,.78);">Akses cepat untuk pembaca aktif</div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Kategori --}}
  <!-- <section class="py-3">
    <h5 class="mb-3">Kategori</h5>
    <div class="d-flex gap-2 flex-wrap">
      @foreach($categories as $category)
        <a href="#" class="btn btn-outline-primary btn-sm">{{ $category->name }}</a>
      @endforeach
    </div>
  </section> -->

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
                <a href="{{ (int) $book->stock > 0 ? route('book.detail', ['id' => $book->id]) : '#' }}" class="btn btn-sm {{ (int) $book->stock > 0 ? 'btn-primary' : 'btn-secondary' }}" {{ (int) $book->stock > 0 ? '' : 'aria-disabled="true" onclick="return false;"' }}>
                  {{ (int) $book->stock > 0 ? 'Pinjam' : 'Stok Habis' }}
                </a>
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
  @guest
    <section class="py-4">
      <div class="card bg-primary text-white overflow-hidden">
        <div class="row g-0 align-items-center">
          <div class="col-md-8 p-4">
            <h4 class="fw-bold">Jadi Anggota Premium</h4>
            <p class="mb-0">Akses baca penuh e-book tanpa batas dan pinjam lebih banyak buku.</p>
          </div>
          <div class="col-md-4 p-4 text-md-end">
            <x-button variant="light" onclick="window.location='{{ route('login') }}'">Upgrade Sekarang</x-button>
          </div>
        </div>
      </div>
    </section>
  @else
    @if(Auth::user()->role === 'pengguna')
      <section class="py-4">
        <div class="card bg-primary text-white overflow-hidden">
          <div class="row g-0 align-items-center">
            <div class="col-md-8 p-4">
              <h4 class="fw-bold">Jadi Anggota Premium</h4>
              <p class="mb-0">Akses baca penuh e-book tanpa batas dan pinjam lebih banyak buku.</p>
            </div>
            <div class="col-md-4 p-4 text-md-end">
              <x-button variant="light" onclick="window.location='{{ route('membership.upgrade.plans') }}'">Upgrade Sekarang</x-button>
            </div>
          </div>
        </div>
      </section>
    @endif
    {{-- Jika role premium: Membership Banner tidak dirender sama sekali (tidak ada HTML, bukan CSS/JS). --}}
  @endguest
</div>
@endsection

