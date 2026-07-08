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
                    @if ($b->status === 'paid')
                      <span class="badge bg-warning bg-opacity-10 text-warning">Dipinjam</span>
                    @elseif ($b->status === 'returned')
                      <span class="badge bg-success bg-opacity-10 text-success">Sudah Dikembalikan</span>
                    @else
                      <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ ucfirst($b->status) }}</span>
                    @endif
                  </td>
                  <!-- Fine -->
                  <td>
                    @if ($b->fine > 0)
                      <span class="text-danger fw-500">Rp{{ number_format($b->fine, 0, ',', '.') }}</span>
                    @else
                      <span class="text-success">Rp0</span>
                    @endif
                  </td>
                  <!-- Action -->
                  <td class="text-center">
                    @if ($b->status === 'paid')
                      <button class="btn btn-sm btn-outline-primary" 
                              data-bs-toggle="modal" 
                              data-bs-target="#returnModal"
                              data-borrowing-id="{{ $b->id }}"
                              data-book-title="{{ $b->book?->title }}">
                        Kembalikan
                      </button>
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
                  @if ($b->status === 'paid')
                    <span class="badge bg-warning bg-opacity-10 text-warning small">Dipinjam</span>
                  @else
                    <span class="badge bg-success bg-opacity-10 text-success small">Sudah Dikembalikan</span>
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
              <button class="btn btn-primary btn-sm w-100" 
                      data-bs-toggle="modal" 
                      data-bs-target="#returnModal"
                      data-borrowing-id="{{ $b->id }}"
                      data-book-title="{{ $b->book?->title }}">
                <i class="fas fa-redo me-2"></i>Kembalikan Buku
              </button>
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
        <button type="button" class="btn btn-primary" id="confirmReturnBtn">
          Kembalikan Buku
        </button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const returnModal = document.getElementById('returnModal');
  
  returnModal.addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    const borrowingId = button.getAttribute('data-borrowing-id');
    const bookTitle = button.getAttribute('data-book-title');
    
    // Update modal
    document.getElementById('bookTitleDisplay').textContent = `"${bookTitle}"`;
    
    // Set form action
    const form = document.getElementById('returnForm');
    form.setAttribute('action', `/borrow/${borrowingId}/return`);
  });

  // Confirm return button
  document.getElementById('confirmReturnBtn').addEventListener('click', function() {
    const form = document.getElementById('returnForm');
    form.style.display = 'block';
    form.submit();
  });
});
</script>
@endpush

