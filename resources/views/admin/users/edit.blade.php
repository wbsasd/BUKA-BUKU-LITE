@extends('layouts.admin')

@section('admin.content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title mb-3">Edit User</h5>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="row g-3">
      @csrf
      @method('PUT')

      <div class="col-md-6">
        <label class="form-label">Nama</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Role</label>
        <select name="role" class="form-select @error('role') is-invalid @enderror">
          <option value="admin" @selected(old('role', $user->role) === 'admin')>admin</option>
          <option value="pengguna" @selected(old('role', $user->role) === 'pengguna')>pengguna</option>
          <option value="premium" @selected(old('role', $user->role) === 'premium')>premium</option>

        </select>
        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Password (opsional)</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror">
        @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-12 d-flex gap-2">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button class="btn btn-primary" type="submit">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection

