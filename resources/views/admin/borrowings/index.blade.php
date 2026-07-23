@extends('layouts.admin')

@section('admin.content')
<!-- Flash Messages -->
@if ($message = Session::get('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if ($message = Session::get('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<!-- Page Title -->
<div class="mb-4">
  <h3 class="fw-bold">Dashboard Peminjaman</h3>
  <p class="text-muted small">Pantau dan kelola semua peminjaman buku di sistem</p>
</div>

<!-- Dashboard Stats Cards -->
<div class="row mb-4">
  <div class="col-md-3 mb-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <p class="text-muted small mb-2">📚 Total Dipinjam</p>
            <h3 class="fw-bold text-primary mb-0">{{ $stats['dipinjam'] ?? 0 }}</h3>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3 mb-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <p class="text-muted small mb-2">⏰ Jatuh Tempo</p>
            <h3 class="fw-bold text-danger mb-0">{{ $stats['jatuh_tempo'] ?? 0 }}</h3>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3 mb-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <p class="text-muted small mb-2">✅ Sudah Dikembalikan</p>
            <h3 class="fw-bold text-success mb-0">{{ $stats['dikembalikan'] ?? 0 }}</h3>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3 mb-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <p class="text-muted small mb-2">💰 Total Denda</p>
            <h3 class="fw-bold text-warning mb-0">Rp{{ number_format($stats['total_denda'] ?? 0, 0, ',', '.') }}</h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-body">
    <form method="GET" action="{{ route('admin.borrowings.index') }}" class="row g-2">
      <div class="col-md-2">
        <input type="text" name="user_search" class="form-control form-control-sm" 
               placeholder="Cari User" value="{{ request('user_search') }}">
      </div>
      <div class="col-md-2">
        <input type="text" name="book_search" class="form-control form-control-sm" 
               placeholder="Cari Buku" value="{{ request('book_search') }}">
      </div>
      <div class="col-md-2">
        <select name="status" class="form-select form-select-sm">
          <option value="">Semua Status</option>
          <option value="paid" @selected(request('status') === 'paid')>Dipinjam</option>
          <option value="overdue" @selected(request('status') === 'overdue')>Jatuh Tempo</option>
          <option value="returned" @selected(request('status') === 'returned')>Dikembalikan</option>
        </select>
      </div>
      <div class="col-md-2">
        <input type="date" name="date_from" class="form-control form-control-sm" 
               placeholder="Dari Tanggal" value="{{ request('date_from') }}">
      </div>
      <div class="col-md-2">
        <input type="date" name="date_to" class="form-control form-control-sm" 
               placeholder="Sampai Tanggal" value="{{ request('date_to') }}">
      </div>
      <div class="col-md-2 d-flex gap-1">
        <button class="btn btn-sm btn-primary flex-grow-1" type="submit">
          <i class="fas fa-search me-1"></i>Cari
        </button>
        <a href="{{ route('admin.borrowings.index') }}" class="btn btn-sm btn-outline-secondary">
          <i class="fas fa-redo"></i>
        </a>
      </div>
    </form>
  </div>
</div>

<!-- Desktop Table View -->
<div class="card border-0 shadow-sm d-none d-lg-block">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="small">Cover</th>
            <th class="small">User</th>
            <th class="small">Judul Buku</th>
            <th class="small">Durasi</th>
            <th class="small">Harga</th>
            <th class="small">Tgl Pinjam</th>
            <th class="small">Jatuh Tempo</th>
            <th class="small">Hari Terlambat</th>
            <th class="small">Denda</th>
            <th class="small">Status</th>
            <th class="small text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($borrowings ?? [] as $b)
            @php
              $isOverdue = $b->actual_status === 'overdue';
              $daysLate = (int) ($b->days_late ?? 0);
              $fine = (int) ($b->fine ?? 0);
            @endphp
            <tr>
              <!-- Cover -->
              <td>
                @if ($b->book?->cover_image)
                  <img src="{{ asset('storage/' . $b->book->cover_image) }}" 
                       alt="{{ $b->book->title }}" 
                       class="rounded" 
                       style="width: 30px; height: 45px; object-fit: cover;">
                @else
                  <i class="fas fa-book text-muted"></i>
                @endif
              </td>

              <!-- User -->
              <td>
                <small class="fw-500">{{ $b->user?->name }}</small>
              </td>

              <!-- Book Title -->
              <td>
                <small>{{ $b->book?->title }}</small>
              </td>

              <!-- Duration -->
              <td>
                <span class="badge bg-info bg-opacity-10 text-info small">{{ $b->duration }} hari</span>
              </td>

              <!-- Price -->
              <td>
                <small class="fw-500">Rp{{ number_format($b->price, 0, ',', '.') }}</small>
              </td>

              <!-- Borrow Date -->
              <td>
                <small class="text-muted">{{ $b->borrowed_at?->format('d M Y') }}</small>
              </td>

              <!-- Due Date -->
              <td>
                <small class="text-muted">{{ $b->due_date?->format('d M Y') }}</small>
              </td>

              <!-- Days Late -->
              <td>
                @if ($isOverdue)
                  <span class="text-danger fw-bold">{{ (int) $daysLate }} hari</span>
                @else
                  <span class="text-success">-</span>
                @endif
              </td>

              <!-- Fine -->
              <td>
                @if ($fine > 0)
                  <span class="text-danger fw-bold">Rp{{ number_format((int) $fine, 0, ',', '.') }}</span>
                @else
                  <span class="text-success">Rp0</span>
                @endif
              </td>

              <!-- Status -->
              <td>
                @if ($b->status === 'returned')
                  <span class="badge bg-success bg-opacity-10 text-success small">Sudah Dikembalikan</span>
                @elseif ($isOverdue)
                  <span class="badge bg-danger bg-opacity-10 text-danger small">Jatuh Tempo</span>
                @else
                  <span class="badge bg-primary bg-opacity-10 text-primary small">Dipinjam</span>
                @endif
              </td>

              <!-- Action -->
              <td class="text-center">
                @if ($isOverdue && !$b->warning_sent)
                  <button class="btn btn-sm btn-danger" 
                          data-bs-toggle="modal" 
                          data-bs-target="#warningModal"
                          data-borrowing-id="{{ $b->id }}"
                          data-user-name="{{ $b->user?->name }}"
                          data-book-title="{{ $b->book?->title }}"
                          data-due-date="{{ $b->due_date?->format('d M Y') }}"
                          data-days-late="{{ $daysLate }}"
                          data-fine="{{ $fine }}">
                    <i class="fas fa-exclamation-triangle me-1"></i>Peringatan
                  </button>
                @elseif ($b->warning_sent)
                  <button class="btn btn-sm btn-secondary" disabled>
                    <i class="fas fa-check me-1"></i>Terkirim
                  </button>
                @else
                  <span class="text-muted small">-</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="11" class="text-center text-muted py-4">
                <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                Tidak ada data peminjaman
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Mobile Card View -->
<div class="d-lg-none">
  @forelse($borrowings ?? [] as $b)
    @php
      $isOverdue = $b->actual_status === 'overdue';
      $daysLate = (int) ($b->days_late ?? 0);
      $fine = (int) ($b->fine ?? 0);
    @endphp
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body">
        <!-- Header -->
        <div class="d-flex gap-3 mb-3">
          @if ($b->book?->cover_image)
            <img src="{{ asset('storage/' . $b->book->cover_image) }}" 
                 alt="{{ $b->book->title }}" 
                 class="rounded" 
                 style="width: 50px; height: 75px; object-fit: cover;">
          @else
            <div class="bg-light rounded" style="width: 50px; height: 75px; display: flex; align-items: center; justify-content: center;">
              <i class="fas fa-book text-muted"></i>
            </div>
          @endif
          <div class="flex-grow-1">
            <h6 class="fw-bold mb-1">{{ $b->book?->title }}</h6>
            <p class="small text-muted mb-1">{{ $b->user?->name }}</p>
            <div class="d-flex gap-2">
              @if ($b->status === 'returned')
                <span class="badge bg-success bg-opacity-10 text-success small">Sudah Dikembalikan</span>
              @elseif ($isOverdue)
                <span class="badge bg-danger bg-opacity-10 text-danger small">Jatuh Tempo</span>
              @else
                <span class="badge bg-primary bg-opacity-10 text-primary small">Dipinjam</span>
              @endif
            </div>
          </div>
        </div>

        <!-- Details Grid -->
        <div class="row g-2 mb-3 small">
          <div class="col-6">
            <span class="text-muted d-block">Durasi</span>
            <span class="fw-500">{{ $b->duration }} hari</span>
          </div>
          <div class="col-6">
            <span class="text-muted d-block">Harga</span>
            <span class="fw-500">Rp{{ number_format($b->price, 0, ',', '.') }}</span>
          </div>
          <div class="col-6">
            <span class="text-muted d-block">Tgl Pinjam</span>
            <span class="fw-500">{{ $b->borrowed_at?->format('d M Y') }}</span>
          </div>
          <div class="col-6">
            <span class="text-muted d-block">Jatuh Tempo</span>
            <span class="fw-500">{{ $b->due_date?->format('d M Y') }}</span>
          </div>
          @if ($isOverdue)
            <div class="col-6">
              <span class="text-muted d-block">Hari Terlambat</span>
              <span class="fw-500 text-danger">{{ (int) $daysLate }} hari</span>
            </div>
            <div class="col-6">
              <span class="text-muted d-block">Denda</span>
              <span class="fw-500 text-danger">Rp{{ number_format((int) $fine, 0, ',', '.') }}</span>
            </div>
          @endif
        </div>

        <!-- Action Button -->
        @if ($isOverdue && !$b->warning_sent)
          <button class="btn btn-sm btn-danger w-100" 
                  data-bs-toggle="modal" 
                  data-bs-target="#warningModal"
                  data-borrowing-id="{{ $b->id }}"
                  data-user-name="{{ $b->user?->name }}"
                  data-book-title="{{ $b->book?->title }}"
                  data-due-date="{{ $b->due_date?->format('d M Y') }}"
                  data-days-late="{{ $daysLate }}"
                  data-fine="{{ $fine }}">
            <i class="fas fa-exclamation-triangle me-1"></i>Kirim Peringatan
          </button>
        @elseif ($b->warning_sent)
          <button class="btn btn-sm btn-secondary w-100" disabled>
            <i class="fas fa-check me-1"></i>Peringatan Terkirim
          </button>
        @endif
      </div>
    </div>
  @empty
    <div class="alert alert-info text-center">
      <i class="fas fa-inbox me-2"></i>Tidak ada data peminjaman
    </div>
  @endforelse
</div>

<!-- Pagination -->
@if ($borrowings?->count())
  <div class="mt-4">
    {{ $borrowings->links() }}
  </div>
@endif

<!-- Warning Modal -->
<div class="modal fade" id="warningModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0">
      <div class="modal-header bg-danger bg-opacity-10 border-danger border-opacity-25">
        <h5 class="modal-title fw-bold text-danger">
          <i class="fas fa-exclamation-triangle me-2"></i>Peringatkan User
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="mb-3">Anda akan mengirimkan peringatan kepada user berikut:</p>
        
        <div class="alert alert-light border">
          <p class="small mb-2">
            <span class="text-muted">Nama User:</span> 
            <strong id="warningUserName"></strong>
          </p>
          <p class="small mb-2">
            <span class="text-muted">Judul Buku:</span> 
            <strong id="warningBookTitle"></strong>
          </p>
          <p class="small mb-2">
            <span class="text-muted">Jatuh Tempo:</span> 
            <strong id="warningDueDate"></strong>
          </p>
          <p class="small mb-2">
            <span class="text-muted">Hari Terlambat:</span> 
            <strong class="text-danger" id="warningDaysLate"></strong>
          </p>
          <p class="small mb-0">
            <span class="text-muted">Total Denda:</span> 
            <strong class="text-danger" id="warningFine"></strong>
          </p>
        </div>
      </div>
      <div class="modal-footer">
        <form id="warningForm" method="POST" style="display: none;">
          @csrf
        </form>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmWarningBtn">
          <i class="fas fa-send me-1"></i>Kirim Warning
        </button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const warningModal = document.getElementById('warningModal');
  
  warningModal.addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    const borrowingId = button.getAttribute('data-borrowing-id');
    const userName = button.getAttribute('data-user-name');
    const bookTitle = button.getAttribute('data-book-title');
    const dueDate = button.getAttribute('data-due-date');
    const daysLate = parseInt(button.getAttribute('data-days-late') || '0', 10);
    const fine = parseInt(button.getAttribute('data-fine'));

    // Update modal content
    document.getElementById('warningUserName').textContent = userName;
    document.getElementById('warningBookTitle').textContent = bookTitle;
    document.getElementById('warningDueDate').textContent = dueDate;
    document.getElementById('warningDaysLate').textContent = daysLate + ' hari';
    document.getElementById('warningFine').textContent = 'Rp' + fine.toLocaleString('id-ID');

    // Set form action
    const form = document.getElementById('warningForm');
    form.setAttribute('action', `/admin/borrowings/${borrowingId}/warning`);
  });

  // Confirm warning button
  document.getElementById('confirmWarningBtn').addEventListener('click', function() {
    const form = document.getElementById('warningForm');
    form.style.display = 'block';
    form.submit();
  });
});
</script>
@endpush
@endsection
