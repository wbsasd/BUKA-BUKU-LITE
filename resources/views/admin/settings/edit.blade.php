@extends('layouts.admin')

@section('admin.content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title mb-3">Edit Setting</h5>

    <form method="POST" action="{{ route('admin.settings.update', $setting) }}" class="row g-3">
      @csrf
      @method('PUT')

      <div class="col-md-6">
        <label class="form-label">Key</label>
        <input name="key" value="{{ old('key', $setting->key ?? '') }}" class="form-control @error('key') is-invalid @enderror">
        @error('key')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Value</label>
        <input name="value" value="{{ old('value', $setting->value ?? '') }}" class="form-control @error('value') is-invalid @enderror">
        @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-12 d-flex gap-2">
        <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection

