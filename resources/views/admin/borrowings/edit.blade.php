@extends('layouts.admin')

@section('admin.content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title mb-3">Edit Borrowing</h5>

    <form method="POST" action="{{ route('admin.borrowings.update', $borrowing) }}" class="row g-3">
      @csrf
      @method('PUT')

      <div class="col-md-6">
        <label class="form-label">Pengguna</label>
        <select name="user_id" class="form-select @error('user_id') is-invalid @enderror">
          @foreach($users ?? [] as $u)
            <option value="{{ $u->id }}" @selected(old('user_id', $borrowing->user_id)==$u->id)>{{ $u->name }}</option>
          @endforeach
        </select>
        @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Buku</label>
        <select name="book_id" class="form-select @error('book_id') is-invalid @enderror">
          @foreach($books ?? [] as $bk)
            <option value="{{ $bk->id }}" @selected(old('book_id', $borrowing->book_id)==$bk->id)>{{ $bk->title }}</option>
          @endforeach
        </select>
        @error('book_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-4">
        <label class="form-label">Tanggal Peminjaman</label>
        <input type="date" name="borrow_date" value="{{ old('borrow_date', optional($borrowing->borrow_date)->format('Y-m-d')) }}" class="form-control @error('borrow_date') is-invalid @enderror">
        @error('borrow_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-4">
        <label class="form-label">Tanggal Pengembalian</label>
        <input type="date" name="return_date" value="{{ old('return_date', optional($borrowing->return_date)->format('Y-m-d')) }}" class="form-control @error('return_date') is-invalid @enderror">
        @error('return_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror">
          <option value="dipinjam" @selected(old('status', $borrowing->status)==='dipinjam')>dipinjam</option>
          <option value="dikembalikan" @selected(old('status', $borrowing->status)==='dikembalikan')>dikembalikan</option>
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-12 d-flex gap-2">
        <a href="{{ route('admin.borrowings.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection

