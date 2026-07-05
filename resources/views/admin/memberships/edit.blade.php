@extends('layouts.admin')

@section('admin.content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title mb-3">Edit Membership</h5>

    <form method="POST" action="{{ route('admin.memberships.update', $membership) }}" class="row g-3">
      @csrf
      @method('PUT')

      <div class="col-12">
        <label class="form-label">Nama</label>
        <input name="name" value="{{ old('name', $membership->name ?? '') }}" class="form-control @error('name') is-invalid @enderror">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-12 d-flex gap-2">
        <a href="{{ route('admin.memberships.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection

