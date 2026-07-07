@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="card shadow-sm rounded-3">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Booking — {{ $book->title }}</h5>
        <div class="small text-muted">Langkah 1 dari 3</div>
      </div>

      <div class="row g-4">
        <div class="col-lg-6">
          <div class="card border-0 shadow-sm p-3">
            <div class="d-flex gap-3">
              <img src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('images/placeholder-cover.png') }}" alt="cover" style="width:100px;height:140px;object-fit:cover;border-radius:8px">
              <div>
                <h6 class="mb-1">{{ $book->title }}</h6>
                <div class="text-muted small">{{ $book->author }}</div>
                <div class="mt-2"><span class="badge bg-light text-dark">Stock: {{ $book->stock }}</span></div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <form method="POST" action="{{ route('borrow.store', $book) }}">
            @csrf
            <div class="mb-3">
              <label class="form-label">Pilih Durasi</label>
              <div class="list-group">
                <label class="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <input type="radio" name="duration" value="3" class="form-check-input me-2" required> 3 hari
                    <div class="small text-muted">Rp10.000</div>
                  </div>
                  <div class="text-end small text-muted">Rp10.000</div>
                </label>

                <label class="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <input type="radio" name="duration" value="7" class="form-check-input me-2" required> 7 hari
                    <div class="small text-muted">Rp20.000</div>
                  </div>
                  <div class="text-end small text-muted">Rp20.000</div>
                </label>

                <label class="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <input type="radio" name="duration" value="14" class="form-check-input me-2" required> 14 hari
                    <div class="small text-muted">Rp35.000</div>
                  </div>
                  <div class="text-end small text-muted">Rp35.000</div>
                </label>

                <label class="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <input type="radio" name="duration" value="30" class="form-check-input me-2" required> 30 hari
                    <div class="small text-muted">Rp60.000</div>
                  </div>
                  <div class="text-end small text-muted">Rp60.000</div>
                </label>
              </div>
            </div>

            <div class="d-flex justify-content-end">
              <a href="{{ route('book.detail', ['id' => $book->id]) }}" class="btn btn-outline-secondary me-2">Batal</a>
              <button class="btn btn-primary">Lanjut ke Pembayaran</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
