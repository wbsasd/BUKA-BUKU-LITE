@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="row g-4">
    <div class="col-12 col-lg-4">
      <div class="card">
        <img src="https://picsum.photos/seed/bookdetail/600/900" class="card-img-top img-fluid" alt="Cover Buku">
        <div class="card-body">
          <h5 class="card-title">Judul Buku Contoh</h5>
          <p class="text-muted mb-1">Penulis Contoh</p>
          <div class="mb-2">
            <span class="badge bg-secondary">Kategori: Fiksi</span>
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
            <x-button variant="outline-primary">Baca Trial</x-button>
            <x-button variant="warning">Upgrade Premium</x-button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-8">
      <h3 class="mb-3">Judul Buku Contoh</h3>
      <p class="text-muted">Penulis Contoh · Penerbit Contoh · 2024</p>

      <section class="mb-4">
        <h5>Sinopsis</h5>
        <p class="text-justify">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce sit amet lectus non orci volutpat pretium. Integer vitae nibh ac mauris gravida tincidunt. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Sed at lacus eu quam tincidunt volutpat. Proin a magna non ipsum ultricies tincidunt. Curabitur vitae turpis ac mi volutpat rhoncus.</p>
      </section>

      <section class="mb-4">
        <h6>Kategori</h6>
        <div class="d-flex gap-2 flex-wrap">
          <span class="badge bg-outline-primary border">Fiksi</span>
          <span class="badge bg-outline-primary border">Drama</span>
          <span class="badge bg-outline-primary border">Romantis</span>
        </div>
      </section>

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
