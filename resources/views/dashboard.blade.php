@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <main class="col-lg-9 col-12 py-4">

      <div>
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h4 class="mb-0">Dashboard</h4>
            <small class="text-muted">Ringkasan aktivitas dan rekomendasi untuk Anda</small>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <h5 class="fw-bold">Hello, {{ Auth::user()?->name ?? 'Pengguna' }}!</h5>
              <p class="mb-0 text-muted">Lanjutkan membaca dan lihat rekomendasi terbaru untuk Anda.</p>
            </div>
            <div>
              <img src="https://picsum.photos/seed/user/120/120" class="rounded-circle" alt="avatar">
            </div>
          </div>
        </div>

        <div class="row g-4">
          <div class="col-lg-8">
            <div class="mb-4">
              <h6>Continue Reading</h6>
              <div class="row g-3">
                {{-- Continue Reading saat ini dibiarkan kosong bila belum ada histori (sesuai instruksi), tanpa mengubah layout --}}
                @if($latestBooks->isNotEmpty())
                  @foreach($latestBooks->take(3) as $book)
                    <div class="col-12 col-md-6">
                      <div class="d-flex gap-3 align-items-center p-3 border rounded">
                        <img
                          src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('images/placeholder-cover.png') }}"
                          alt="cover"
                          class="img-fluid"
                          style="width:60px;height:80px;object-fit:cover"
                        >
                        <div class="flex-grow-1">
                          <div class="fw-semibold">{{ $book->title }}</div>
                          <div class="small text-muted">{{ $book->author }} · Hal. 12/200</div>
                          <div class="mt-2">
                            <div class="progress" style="height:6px">
                              <div class="progress-bar" role="progressbar" style="width: 30%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </div>
                        </div>
                        <div>
                          <a href="{{ route('reader', ['id' => $book->id]) }}" class="btn btn-sm btn-outline-primary">Lanjutkan</a>
                        </div>
                      </div>
                    </div>
                  @endforeach
                @endif
              </div>
            </div>

            <div class="mb-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Rekomendasi Buku</h6>
                <a href="#" class="small">Lihat semua</a>
              </div>

@if($recommendedBooks->isEmpty())
                @include('user.empty-books-message')
              @else
                <div class="row g-3">
                  @foreach($recommendedBooks->take(4) as $book)
                    <div class="col-6 col-md-3">
                      <x-book-card
                        :bookId="$book->id"
                        title="{{ $book->title }}"
                        author="{{ $book->author }}"
                        cover="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : null }}"
                        rating="4"
                      >
                        <div class="mt-2 d-grid">
                          <a href="{{ route('book.detail', ['id' => $book->id]) }}" class="btn btn-sm btn-primary">Pinjam</a>
                        </div>
                      </x-book-card>
                    </div>
                  @endforeach
                </div>
              @endif
            </div>

            <div class="mb-4">
              <h6 class="mb-2">Kategori</h6>
              <div class="d-flex gap-2 flex-wrap">
                @foreach($categories as $cat)
                  <a href="#" class="btn btn-outline-secondary btn-sm">{{ $cat->name }}</a>
                @endforeach
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="mb-3">
              <x-membership-card role="Anggota Premium" name="Anna" expires="31 Des 2026">
                <div class="mt-2 d-grid">
                  <a href="#" class="btn btn-sm btn-outline-light btn-primary">Kelola Keanggotaan</a>
                </div>
              </x-membership-card>
            </div>

            <div class="card mb-3">
              <div class="card-body">
                <h6 class="mb-1">Rekomendasi Untuk Anda</h6>
                <p class="small text-muted mb-2">Berdasarkan bacaan terakhir Anda</p>
                <ul class="list-unstyled small">
@if($recommendedBooks->isEmpty())
                    <li class="py-2 text-muted">
                      @include('user.empty-books-message')
                    </li>
                  @else
                    @foreach($recommendedBooks->take(3) as $book)
                      <li class="py-2 d-flex justify-content-between align-items-center border-bottom">
                        <div>
                          <div class="fw-semibold">{{ $book->title }}</div>
                          <div class="text-muted">{{ $book->author }}</div>
                        </div>
                        <a href="{{ route('reader', ['id' => $book->id]) }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                      </li>
                    @endforeach
                  @endif
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>
@endsection



