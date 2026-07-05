@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <aside class="col-12 col-md-2 d-none d-md-block">
      <x-sidebar>
        <a class="nav-link" href="#">Daftar Buku</a>
        <a class="nav-link" href="#">Favorit</a>
        <a class="nav-link" href="#">Riwayat</a>
      </x-sidebar>
    </aside>

    <main class="col-12 col-md-7">
      <div class="card pdf-reader-wrapper">
        <div class="card-body">
          <div id="pdfViewer" class="pdf-viewer text-center mb-3">
            <img id="pdfPageImage" src="" alt="PDF Page">
          </div>

          <div class="d-flex align-items-center justify-content-between mb-2 gap-2">
            <div class="d-flex gap-2">
              <button id="prevBtn" class="btn btn-outline-secondary btn-sm">&laquo; Prev</button>
              <button id="nextBtn" class="btn btn-outline-secondary btn-sm">Next &raquo;</button>
            </div>
            <div>Halaman <span id="pageNum">1</span> / <span id="totalPages">12</span></div>
            <div class="w-50">
              <div class="progress" style="height:8px">
                <div id="progressBar" class="progress-bar" role="progressbar" style="width:0%"></div>
              </div>
            </div>
          </div>

        </div>

        <div id="pdfOverlay" class="pdf-overlay d-none">
          <div class="card p-4 text-center">
            <h5 class="fw-semibold">Halaman Terkunci</h5>
            <p>Anda perlu menjadi Anggota Premium untuk membaca lebih dari 5 halaman.</p>
            <div class="d-flex justify-content-center gap-2 mt-3">
              <button id="upgradeBtn" class="btn btn-warning">Upgrade Premium</button>
              <button id="closeOverlayBtn" class="btn btn-light">Kembali ke Halaman 5</button>
            </div>
          </div>
        </div>
      </div>
    </main>

    <aside class="col-12 col-md-3">
      <div class="card mb-3">
        <div class="card-body">
          <h5>Informasi Buku</h5>
          <p class="mb-1 fw-semibold">{{ $book->title }}</p>
          <p class="small text-muted">{{ $book->author }} · {{ $book->publisher }} · {{ $book->publication_year }}</p>
          <div class="mb-2 text-warning">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-half"></i>
            <span class="small text-muted ms-2">4.5 (200)</span>
          </div>
          <div class="mb-2">
            <strong>Progress Membaca</strong>
            <div class="progress mt-2" style="height:8px">
              <div id="sideProgress" class="progress-bar" role="progressbar" style="width:0%"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
<div class="card-body">
          <h6>Buku Terkait</h6>
          <div class="row g-2">
@foreach($relatedBooks as $rel)
              <div class="col-6">
                <x-book-card
                  title="{{ $rel->title }}"
                  author="{{ $rel->author }}"
                  cover="{{ $rel->cover_image ? asset('storage/covers/'.$rel->cover_image) : null }}"
                  rating="4"
                >
                  <div class="mt-2 d-grid">
                    <a href="#" class="btn btn-sm btn-outline-primary">Lihat</a>
                  </div>
                </x-book-card>
              </div>
@endforeach
          </div>
        </div>
      </div>
    </aside>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const totalPages = 1;
  let currentPage = 1;
  let isPremium = {{ auth()->user()?->role === 'premium' ? 'true' : 'false' }};

  const pageNumEl = document.getElementById('pageNum');
  const totalPagesEl = document.getElementById('totalPages');
  const pdfImg = document.getElementById('pdfPageImage');
  const progressBar = document.getElementById('progressBar');
  const sideProgress = document.getElementById('sideProgress');
  const pdfImgAlt = document.getElementById('pdfPageImage');
  const overlay = document.getElementById('pdfOverlay');
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  const upgradeBtn = document.getElementById('upgradeBtn');
  const closeOverlayBtn = document.getElementById('closeOverlayBtn');

  totalPagesEl.textContent = totalPages;

  function updateViewer(){
    pageNumEl.textContent = currentPage;
    // Load real PDF (first page placeholder) - viewer still image-based in this UI
    pdfImg.src = '{{ asset('storage/pdfs/'.$book->file_pdf) }}';
    const pct = Math.round((currentPage / totalPages) * 100);
    progressBar.style.width = pct + '%';
    sideProgress.style.width = pct + '%';

    if(!isPremium && currentPage > 5){
      overlay.classList.remove('d-none');
    } else {
      overlay.classList.add('d-none');
    }

    prevBtn.disabled = currentPage <= 1;
    nextBtn.disabled = currentPage >= totalPages;
  }

  prevBtn.addEventListener('click', function(){
    if(currentPage > 1){ currentPage--; updateViewer(); }
  });

  nextBtn.addEventListener('click', function(){
    if(currentPage < totalPages){ currentPage++; updateViewer(); }
  });

  upgradeBtn.addEventListener('click', function(){
    isPremium = true;
    overlay.classList.add('d-none');
    // allow access beyond page 5
  });

  closeOverlayBtn.addEventListener('click', function(){
    currentPage = 5;
    updateViewer();
  });

  updateViewer();
});
</script>
@endpush

@endsection
