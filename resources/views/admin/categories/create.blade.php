@extends('layouts.admin')

@section('admin.content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title mb-3">Tambah Category</h5>

    <form method="POST" action="{{ route('admin.categories.store') }}" class="row g-3">
      @csrf

      <div class="col-12">
        <label class="form-label">Nama</label>
        <input name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-12 d-flex gap-2">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection

