@extends('layouts.guest')

@section('content')
  <div class="text-center mb-4">
    <span class="badge bg-danger rounded-pill">Admin Area</span>
    <h2 class="mt-4 fw-bold">Admin Masuk</h2>
    <p class="text-muted">Masuk sebagai administrator untuk mengelola sistem.</p>
  </div>

  <form method="POST" action="{{ route('admin.login.post') }}">
    @csrf

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
      @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
      @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label" for="remember">Ingat Saya</label>
      </div>
    </div>

    <button type="submit" class="btn btn-danger w-100 py-2">Masuk sebagai Admin</button>
  </form>

@endsection
