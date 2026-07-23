@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <main class="col-12 col-md-7">
      <div class="card pdf-reader-wrapper">
        <div class="card-body">
          <div id="pdfViewer" class="pdf-viewer text-center mb-3">
            @if(!empty($book->file_pdf) && ($documentAvailable ?? false))
              <div class="border rounded p-2 bg-light">
                <div class="d-flex align-items-center justify-content-between mb-2 gap-2 flex-wrap">
                  <div class="d-flex gap-2">
                    <button id="prevBtn" class="btn btn-outline-secondary btn-sm" type="button">&laquo; Prev</button>
                    <button id="nextBtn" class="btn btn-outline-secondary btn-sm" type="button">Next &raquo;</button>
                  </div>
                  <div class="small text-muted d-flex align-items-center gap-2">
                    <label for="pageNumberInput" class="mb-0">Halaman</label>
                    <input id="pageNumberInput" type="number" min="1" class="form-control form-control-sm" style="width:70px;" value="1">
                    <span>/</span>
                    <span id="totalPages">1</span>
                  </div>
                  <div class="d-flex gap-2">
                    <button id="zoomOutBtn" class="btn btn-outline-secondary btn-sm" type="button">Zoom Out</button>
                    <button id="zoomInBtn" class="btn btn-outline-secondary btn-sm" type="button">Zoom In</button>
                  </div>
                </div>
                <div id="pdfCanvasContainer" class="pdf-canvas-container">
                  <canvas id="pdfCanvas" class="img-fluid rounded shadow-sm"></canvas>
                </div>
                <div id="pdfStatus" class="small text-muted mt-2">Memuat PDF...</div>
              </div>
            @else
              <div class="border rounded p-4 bg-light text-muted">PDF tidak tersedia.</div>
            @endif
          </div>

        </div>

        <div id="pdfOverlay" class="pdf-overlay d-none">
          <div class="card p-4 text-center">
            <h5 class="fw-semibold">Halaman Terkunci</h5>
            <p>Anda perlu menjadi Anggota Premium untuk membaca lebih dari 10 halaman.</p>
            <div class="d-flex justify-content-center gap-2 mt-3">
              <button id="upgradeBtn" class="btn btn-warning">Upgrade Premium</button>
              <button id="closeOverlayBtn" class="btn btn-light">Kembali ke Halaman 10</button>
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
                  cover="{{ $rel->cover_image ? asset('storage/'.$rel->cover_image) : null }}"
                  rating="4"
                >
                  <div class="mt-2 d-grid">
                    <a href="{{ route('book.detail', ['id' => $rel->id]) }}" class="btn btn-sm btn-outline-primary">Lihat</a>
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

@push('styles')
<style>
  .pdf-canvas-container {
    overflow: auto;
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 0.5rem;
    min-height: 460px;
  }

  #pdfCanvas {
    max-width: 100%;
    display: block;
    margin: 0 auto;
  }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const pdfUrl = @json(!empty($book->file_pdf) ? route('reader.document', ['id' => $book->id, 'token' => $readerToken ?? '']) : null);
  const overlay = document.getElementById('pdfOverlay');
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  const zoomInBtn = document.getElementById('zoomInBtn');
  const zoomOutBtn = document.getElementById('zoomOutBtn');
  const upgradeBtn = document.getElementById('upgradeBtn');
  const closeOverlayBtn = document.getElementById('closeOverlayBtn');
  const pageNumberInput = document.getElementById('pageNumberInput');
  const totalPagesEl = document.getElementById('totalPages');
  const pdfStatusEl = document.getElementById('pdfStatus');
  const canvas = document.getElementById('pdfCanvas');
  const ctx = canvas.getContext('2d');

  let currentPage = 1;
  let totalPages = 1;
  let currentScale = 1.25;
  let pdfDoc = null;
  const trialLimit = {{ (int) ($trialLimit ?? 10) }};
  let isPremium = {{ auth()->user()?->hasPremiumAccess() ? 'true' : 'false' }};

  if (!pdfUrl) {
    if (pdfStatusEl) {
      pdfStatusEl.textContent = 'PDF tidak tersedia.';
    }
    return;
  }

  function bootPdfReader() {
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

  function updateControls(){
    if (prevBtn) prevBtn.disabled = currentPage <= 1;
    if (nextBtn) nextBtn.disabled = currentPage >= totalPages;
    if (zoomOutBtn) zoomOutBtn.disabled = currentScale <= 0.8;
    if (zoomInBtn) zoomInBtn.disabled = currentScale >= 2.5;

    if (!isPremium && currentPage > trialLimit) {
      overlay.classList.remove('d-none');
    } else {
      overlay.classList.add('d-none');
    }
  }

  function renderCurrentPage(){
    if (!pdfDoc) return;

    pdfDoc.getPage(currentPage).then(function(page){
      const viewport = page.getViewport({ scale: currentScale });
      canvas.height = viewport.height;
      canvas.width = viewport.width;
      if (pageNumberInput) {
        pageNumberInput.value = currentPage;
      }
      totalPagesEl.textContent = totalPages;

      const renderContext = {
        canvasContext: ctx,
        viewport: viewport
      };

      page.render(renderContext).promise.then(function(){
        updateControls();
      });
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', function(){
      if (currentPage > 1) {
        currentPage--;
        renderCurrentPage();
      }
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', function(){
      if (!isPremium && currentPage >= trialLimit) {
        currentPage = trialLimit + 1;
        updateControls();
        return;
      }

      if (currentPage < totalPages) {
        currentPage++;
        renderCurrentPage();
      }
    });
  }

  if (pageNumberInput) {
    pageNumberInput.addEventListener('change', function(){
      const requestedPage = parseInt(pageNumberInput.value, 10);
      if (!isPremium && requestedPage > trialLimit) {
        currentPage = trialLimit + 1;
        updateControls();
        return;
      }

      if (!Number.isNaN(requestedPage) && requestedPage >= 1 && requestedPage <= totalPages) {
        currentPage = requestedPage;
        renderCurrentPage();
      } else {
        pageNumberInput.value = currentPage;
      }
    });
  }

  if (zoomInBtn) {
    zoomInBtn.addEventListener('click', function(){
      currentScale = Math.min(2.5, currentScale + 0.25);
      renderCurrentPage();
    });
  }

  if (zoomOutBtn) {
    zoomOutBtn.addEventListener('click', function(){
      currentScale = Math.max(0.8, currentScale - 0.25);
      renderCurrentPage();
    });
  }

  if (upgradeBtn) {
    upgradeBtn.addEventListener('click', function(){
      const upgradeUrl = @json(auth()->check() ? route('membership.upgrade.plans') : route('membership.register'));
      window.location.href = upgradeUrl;
    });
  }

  if (closeOverlayBtn) {
    closeOverlayBtn.addEventListener('click', function(){
      currentPage = trialLimit;
      renderCurrentPage();
    });
  }

  pdfjsLib.getDocument({
    url: pdfUrl,
    cMapUrl: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/cmaps/',
    cMapPacked: true
  }).promise.then(function(pdf){
    pdfDoc = pdf;
    totalPages = pdf.numPages;
    totalPagesEl.textContent = totalPages;
    renderCurrentPage();
    if (pdfStatusEl) {
      pdfStatusEl.textContent = '';
    }
  }).catch(function(){
    if (pdfStatusEl) {
      pdfStatusEl.textContent = 'PDF tidak tersedia.';
    }
  });

  updateControls();
  }

  if (typeof window.pdfjsLib === 'undefined') {
    const fallbackScript = document.createElement('script');
    fallbackScript.src = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@2.16.105/build/pdf.min.js';
    fallbackScript.onload = function() {
      if (typeof window.pdfjsLib !== 'undefined') {
        bootPdfReader();
      } else if (pdfStatusEl) {
        pdfStatusEl.textContent = 'PDF reader gagal dimuat.';
      }
    };
    fallbackScript.onerror = function() {
      if (pdfStatusEl) {
        pdfStatusEl.textContent = 'PDF reader gagal dimuat.';
      }
    };
    document.head.appendChild(fallbackScript);
    return;
  }

  bootPdfReader();
});
</script>
@endpush

@endsection
