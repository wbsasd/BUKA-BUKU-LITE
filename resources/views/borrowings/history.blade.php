@extends('layouts.app')

@section('content')
<div class="container py-4">
  <!-- Flash Messages -->
  @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ $message }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if ($message = Session::get('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ $message }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <!-- Page Title -->
  <div class="mb-4">
    <h2 class="fw-bold mb-1">Riwayat Peminjaman</h2>
    <p class="text-muted">Kelola dan pantau semua peminjaman buku Anda</p>
  </div>

  <!-- Summary Cards -->
  <div class="row mb-4">
    <div class="col-md-4 mb-3 mb-md-0">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <p class="text-muted small mb-2">Buku Sedang Dipinjam</p>
              <h3 class="fw-bold text-warning mb-0">{{ $borrowings->sum(fn($b) => $b->status === 'paid' ? 1 : 0) }}</h3>
            </div>
            <div class="bg-warning bg-opacity-10 p-3 rounded-2">
              <i class="fas fa-book text-warning"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4 mb-3 mb-md-0">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <p class="text-muted small mb-2">Buku Sudah Dikembalikan</p>
              <h3 class="fw-bold text-success mb-0">{{ $borrowings->sum(fn($b) => $b->status === 'returned' ? 1 : 0) }}</h3>
            </div>
            <div class="bg-success bg-opacity-10 p-3 rounded-2">
              <i class="fas fa-check-circle text-success"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <p class="text-muted small mb-2">Total Denda</p>
              <h3 class="fw-bold text-danger mb-0">Rp{{ number_format($borrowings->sum(fn($b) => $b->fine), 0, ',', '.') }}</h3>
            </div>
            <div class="bg-danger bg-opacity-10 p-3 rounded-2">
              <i class="fas fa-exclamation-circle text-danger"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Empty State -->
  @if ($borrowings->count() === 0)
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body py-5 text-center">
        <div class="mb-3">
          <i class="fas fa-book-open" style="font-size: 3rem; color: #ccc;"></i>
        </div>
        <h5 class="text-muted mb-2">Belum Ada Peminjaman</h5>
        <p class="text-muted small mb-3">Mulai pinjam buku untuk melihat riwayat peminjaman Anda di sini</p>
        <a href="{{ route('home') }}" class="btn btn-primary btn-sm">
          <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
        </a>
      </div>
    </div>
  @else
    <!-- Warnings for Overdue Books -->
    @php
      $warningBorrowings = $borrowings->filter(fn($b) => $b->warning_sent);
      $totalFine = $warningBorrowings->sum(fn($b) => $b->fine);
    @endphp

    @if($warningBorrowings->count() > 0)
      <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>⚠</strong> Buku Anda sudah melewati batas waktu peminjaman. Segera kembalikan buku.
        <br>
        <small class="mt-2 d-block">Total denda saat ini: <strong class="text-danger">Rp{{ number_format($totalFine, 0, ',', '.') }}</strong></small>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>

      <div class="d-flex gap-2 mb-4">
        @php
          $firstWarn = $warningBorrowings->first();
        @endphp
        <button class="btn btn-danger flex-grow-1"
                data-bs-toggle="modal"
                data-bs-target="#returnModal"
                data-borrowing-id="{{ $firstWarn->id }}"
                data-book-title="{{ $firstWarn->book?->title }}">
          <i class="fas fa-redo me-2"></i>Kembalikan Buku
        </button>
        <button class="btn btn-warning flex-grow-1"
                data-bs-toggle="modal"
                data-bs-target="#extendModal"
                data-borrowing-id="{{ $firstWarn->id }}"
                data-book-title="{{ $firstWarn->book?->title }}">
          <i class="fas fa-plus me-2"></i>Pinjam Lebih Lama
        </button>
      </div>
    @endif

    <!-- Desktop View (Table) -->
    <div class="card border-0 shadow-sm rounded-3 d-none d-md-block">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead>
              <tr class="border-bottom">
                <th class="text-muted small">Buku</th>
                <th class="text-muted small">Durasi</th>
                <th class="text-muted small">Harga</th>
                <th class="text-muted small">Tanggal Pinjam</th>
                <th class="text-muted small">Jatuh Tempo</th>
                <th class="text-muted small">Status</th>
                <th class="text-muted small">Denda</th>
                <th class="text-muted small text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($borrowings as $b)
      @php
        $isOverdue = $b->status === 'paid' && now() > $b->due_date;
        $daysLate = $isOverdue ? (int) $b->due_date->diffInDays(now()) : 0;
      @endphp
                <tr class="border-bottom">
                  <!-- Book -->
                  <td>
                    <div class="d-flex align-items-center">
                      <img src="{{ asset('storage/' . $b->book?->cover_image) }}" 
                           alt="{{ $b->book?->title }}" 
                           class="rounded me-2" 
                           style="width: 40px; height: 60px; object-fit: cover;">
                      <div>
                        <div class="fw-500 small">{{ $b->book?->title }}</div>
                      </div>
                    </div>
                  </td>
                  <!-- Duration -->
                  <td>
                    <span class="badge bg-info bg-opacity-10 text-info">{{ $b->duration }} hari</span>
                  </td>
                  <!-- Price -->
                  <td>
                    <span class="fw-500">Rp{{ number_format($b->price, 0, ',', '.') }}</span>
                  </td>
                  <!-- Borrow Date -->
                  <td>
                    <small class="text-muted">{{ $b->borrowed_at?->format('d M Y') }}</small>
                  </td>
                  <!-- Due Date -->
                  <td>
                    <small class="text-muted">{{ $b->due_date?->format('d M Y') }}</small>
                  </td>
                  <!-- Status -->
                  <td>
                    @if ($b->status === 'returned')
                      <span class="badge bg-success bg-opacity-10 text-success">Sudah Dikembalikan</span>
                    @elseif ($isOverdue)
                      <span class="badge bg-danger bg-opacity-10 text-danger">Jatuh Tempo</span>
                    @else
                      <span class="badge bg-warning bg-opacity-10 text-warning">Dipinjam</span>
                    @endif
                  </td>
                  <!-- Fine -->
                  <td>
                @if ($b->fine > 0)
                  <span class="text-danger fw-500">Rp{{ number_format((int) $b->fine, 0, ',', '.') }}</span>
                @else
                      <span class="text-success">Rp0</span>
                    @endif
                  </td>
                  <!-- Action -->
                  <td class="text-center">
                    @if ($b->status === 'paid')
                      <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-outline-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#returnModal"
                                data-borrowing-id="{{ $b->id }}"
                                data-book-title="{{ $b->book?->title }}"
                                title="Kembalikan buku">
                          <i class="fas fa-redo me-1"></i>Kembalikan
                        </button>
                        <button class="btn btn-outline-warning" 
                                data-bs-toggle="modal" 
                                data-bs-target="#extendModal"
                                data-borrowing-id="{{ $b->id }}"
                                data-book-title="{{ $b->book?->title }}"
                                title="Perpanjang masa pinjam">
                          <i class="fas fa-plus me-1"></i>Perpanjang
                        </button>
                      </div>
                    @else
                      <span class="badge bg-success bg-opacity-10 text-success">Sudah Dikembalikan</span>
                    @endif
                  </td>
                </tr>
              @empty
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Mobile View (Card List) -->
    <div class="d-md-none">
      @forelse($borrowings as $b)
        @php
          $isOverdue = $b->status === 'paid' && now() > $b->due_date;
        @endphp
        <div class="card border-0 shadow-sm rounded-3 mb-3">
          <div class="card-body">
            <!-- Book Header -->
            <div class="d-flex mb-3">
              <img src="{{ asset('storage/' . $b->book?->cover_image) }}" 
                   alt="{{ $b->book?->title }}" 
                   class="rounded me-3" 
                   style="width: 60px; height: 90px; object-fit: cover;">
              <div class="flex-grow-1">
                <h6 class="fw-bold mb-1">{{ $b->book?->title }}</h6>
                <div class="d-flex gap-2 flex-wrap">
                  @if ($b->status === 'returned')
                    <span class="badge bg-success bg-opacity-10 text-success small">Sudah Dikembalikan</span>
                  @elseif ($isOverdue)
                    <span class="badge bg-danger bg-opacity-10 text-danger small">Jatuh Tempo</span>
                  @else
                    <span class="badge bg-warning bg-opacity-10 text-warning small">Dipinjam</span>
                  @endif
                  @if ($b->fine > 0)
                    <span class="badge bg-danger bg-opacity-10 text-danger small">Denda: Rp{{ number_format($b->fine, 0, ',', '.') }}</span>
                  @endif
                </div>
              </div>
            </div>

            <!-- Details Grid -->
            <div class="row g-2 mb-3">
              <div class="col-6">
                <small class="text-muted d-block">Durasi</small>
                <span class="fw-500 small">{{ $b->duration }} hari</span>
              </div>
              <div class="col-6">
                <small class="text-muted d-block">Harga</small>
                <span class="fw-500 small">Rp{{ number_format($b->price, 0, ',', '.') }}</span>
              </div>
              <div class="col-6">
                <small class="text-muted d-block">Tanggal Pinjam</small>
                <span class="fw-500 small">{{ $b->borrowed_at?->format('d M Y') }}</span>
              </div>
              <div class="col-6">
                <small class="text-muted d-block">Jatuh Tempo</small>
                <span class="fw-500 small">{{ $b->due_date?->format('d M Y') }}</span>
              </div>
            </div>

            <!-- Action -->
            @if ($b->status === 'paid')
              <div class="d-flex gap-2">
                <button class="btn btn-danger btn-sm flex-grow-1" 
                        data-bs-toggle="modal" 
                        data-bs-target="#returnModal"
                        data-borrowing-id="{{ $b->id }}"
                        data-book-title="{{ $b->book?->title }}">
                  <i class="fas fa-redo me-1"></i>Kembalikan
                </button>
                <button class="btn btn-warning btn-sm flex-grow-1" 
                        data-bs-toggle="modal" 
                        data-bs-target="#extendModal"
                        data-borrowing-id="{{ $b->id }}"
                        data-book-title="{{ $b->book?->title }}">
                  <i class="fas fa-plus me-1"></i>Perpanjang
                </button>
              </div>
            @else
              <div class="alert alert-success alert-sm mb-0">
                <i class="fas fa-check-circle me-2"></i>Sudah Dikembalikan
              </div>
            @endif
          </div>
        </div>
      @empty
      @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
      {{ $borrowings->links() }}
    </div>
  @endif
</div>

<!-- Return Confirmation Modal -->
<div class="modal fade" id="returnModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-3">
      <div class="modal-body p-4 text-center">
        <div class="mb-3">
          <i class="fas fa-book-open" style="font-size: 3rem; color: #ffc107;"></i>
        </div>
        <h5 class="fw-bold mb-2">Konfirmasi Pengembalian</h5>
        <p class="text-muted mb-1">Apakah Anda yakin ingin mengembalikan buku ini?</p>
        <p class="text-muted small" id="bookTitleDisplay"></p>
      </div>
      <div class="modal-footer border-top bg-light rounded-bottom">
        <form id="returnForm" method="POST" style="display: none;">
          @csrf
        </form>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Nanti Dulu Deh
        </button>
        <button type="button" class="btn btn-danger" id="confirmReturnBtn">
          <i class="fas fa-redo me-1"></i>Kembalikan Buku
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Extend Duration Modal -->
<div class="modal fade" id="extendModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-3">
      <div class="modal-header bg-warning bg-opacity-10 border-warning border-opacity-25">
        <h5 class="modal-title fw-bold text-warning">
          <i class="fas fa-plus-circle me-2"></i>Perpanjang Masa Pinjam
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="mb-3">Pilih durasi perpanjangan untuk buku berikut:</p>
        <p class="small mb-3 p-2 bg-light rounded">
          <strong id="extendBookTitle"></strong>
        </p>

        <form id="extendForm" method="POST" style="display: none;">
          @csrf
          <input type="hidden" name="extend_duration">
        </form>

        <div class="row g-2">
          <div class="col-6 col-sm-6">
            <button class="btn btn-outline-warning w-100 text-start extend-option" data-duration="3" data-price="10000">
              <div class="fw-bold">+ 3 Hari</div>
              <small class="text-muted">Rp10.000</small>
            </button>
          </div>
          <div class="col-6 col-sm-6">
            <button class="btn btn-outline-warning w-100 text-start extend-option" data-duration="7" data-price="20000">
              <div class="fw-bold">+ 7 Hari</div>
              <small class="text-muted">Rp20.000</small>
            </button>
          </div>
          <div class="col-6 col-sm-6">
            <button class="btn btn-outline-warning w-100 text-start extend-option" data-duration="14" data-price="35000">
              <div class="fw-bold">+ 14 Hari</div>
              <small class="text-muted">Rp35.000</small>
            </button>
          </div>
          <div class="col-6 col-sm-6">
            <button class="btn btn-outline-warning w-100 text-start extend-option" data-duration="30" data-price="60000">
              <div class="fw-bold">+ 30 Hari</div>
              <small class="text-muted">Rp60.000</small>
            </button>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Return Modal Handler
  const returnModal = document.getElementById('returnModal');
  returnModal.addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    const borrowingId = button.getAttribute('data-borrowing-id');
    const bookTitle = button.getAttribute('data-book-title');
    
    document.getElementById('bookTitleDisplay').textContent = `"${bookTitle}"`;
    const form = document.getElementById('returnForm');
    form.setAttribute('action', `/borrow/${borrowingId}/return`);
  });

  document.getElementById('confirmReturnBtn').addEventListener('click', function() {
    document.getElementById('returnForm').submit();
  });

  // Extend Modal Handler
  const extendModal = document.getElementById('extendModal');
  extendModal.addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    const borrowingId = button.getAttribute('data-borrowing-id');
    const bookTitle = button.getAttribute('data-book-title');
    
    document.getElementById('extendBookTitle').textContent = bookTitle;
    
    // Store borrowing ID in modal for use by extend options
    extendModal.dataset.borrowingId = borrowingId;
  });

  // Extend Duration Option Buttons
  document.querySelectorAll('.extend-option').forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const duration = this.getAttribute('data-duration');
      const borrowingId = extendModal.dataset.borrowingId;
      
      // Create form and submit
      const form = document.getElementById('extendForm');
      form.querySelector('input[name="extend_duration"]').value = duration;
      form.setAttribute('action', `/borrow/${borrowingId}/extend`);
      form.submit();
    });
  });
});
</script>
@endpush

