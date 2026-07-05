@extends('layouts.guest')

@section('content')
  <div class="text-center mb-4">
    <!-- <span class="badge bg-primary rounded-pill">Buka Buku Lite</span> -->
    <h2 class="mt-4 fw-bold">Holla, Selamat Datang</h2>
    <p class="text-muted">Masuk untuk melanjutkan ke dashboard perpustakaan Anda.</p>
  </div>

  <form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
      @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
      @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
        <label class="form-check-label" for="remember">Ingat Saya</label>
      </div>
      @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="small">Lupa Password?</a>
      @endif
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2">Masuk</button>
  </form>

  <div class="text-center mt-4 text-muted small">
    Belum punya akun? Hubungi admin untuk pendaftaran.
  </div>
@endsection
