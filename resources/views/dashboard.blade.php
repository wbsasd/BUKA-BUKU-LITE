@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <aside class="col-12 col-md-3 col-lg-2 mb-3">
      <x-sidebar>
        <a class="nav-link" href="{{ route('home') }}">Beranda</a>
        <a class="nav-link" href="#">Discover</a>
        <a class="nav-link" href="#">Wishlist</a>
        <a class="nav-link" href="#">Pengaturan</a>
      </x-sidebar>
    </aside>

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
                @for($i=1;$i<=3;$i++)
                  <div class="col-12 col-md-6">
                    <div class="d-flex gap-3 align-items-center p-3 border rounded">
                      <img src="https://picsum.photos/seed/cont{{$i}}/80/110" alt="cover" class="img-fluid" style="width:60px;height:80px;object-fit:cover">
                      <div class="flex-grow-1">
                        <div class="fw-semibold">Buku yang sedang dibaca {{$i}}</div>
                        <div class="small text-muted">Penulis {{$i}} · Hal. 12/200</div>
                        <div class="mt-2">
                          <div class="progress" style="height:6px">
                            <div class="progress-bar" role="progressbar" style="width: {{ 30*$i }}%;" aria-valuenow="{{ 30*$i }}" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                      </div>
                      <div>
                        <a href="#" class="btn btn-sm btn-outline-primary">Lanjutkan</a>
                      </div>
                    </div>
                  </div>
                @endfor
              </div>
            </div>

            <div class="mb-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Rekomendasi Buku</h6>
                <a href="#" class="small">Lihat semua</a>
              </div>
              <div class="row g-3">
                @for($i=1;$i<=4;$i++)
                  <div class="col-6 col-md-3">
                    <x-book-card title="Rekomendasi {{$i}}" author="Pengarang {{$i}}" cover="https://picsum.photos/seed/rec{{$i}}/300/420" rating="4">
                      <div class="mt-2 d-grid">
                        <a href="#" class="btn btn-sm btn-primary">Baca</a>
                      </div>
                    </x-book-card>
                  </div>
                @endfor
              </div>
            </div>

            <div class="mb-4">
              <h6 class="mb-2">Kategori</h6>
              <div class="d-flex gap-2 flex-wrap">
                @foreach(['Fiksi','Biografi','Teknologi','Self-Help','Anak'] as $cat)
                  <a href="#" class="btn btn-outline-secondary btn-sm">{{ $cat }}</a>
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
                  @for($i=1;$i<=3;$i++)
                    <li class="py-2 d-flex justify-content-between align-items-center border-bottom">
                      <div>
                        <div class="fw-semibold">Buku Rekomendasi {{$i}}</div>
                        <div class="text-muted">Penulis {{$i}}</div>
                      </div>
                      <a href="#" class="btn btn-sm btn-outline-primary">Lihat</a>
                    </li>
                  @endfor
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

