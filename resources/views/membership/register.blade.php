@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between gap-3 mb-4">
                        <div>
                            <h1 class="h4 mb-1">Registrasi Membership</h1>
                            <p class="text-muted mb-0">Lengkapi data Anda untuk mengajukan membership.</p>
                        </div>
                        <span class="badge bg-primary-soft text-primary">Form Membership</span>
                    </div>

                    <form method="POST" action="{{ route('membership.store') }}" enctype="multipart/form-data" novalidate>
                        @csrf


                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="full_name" placeholder="Masukkan nama lengkap" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Masukkan email" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" placeholder="Masukkan username" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Buat password" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Konfirmasi password" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Nomor HP</label>
                                <input type="text" class="form-control" name="phone" placeholder="Contoh: 08xxxxxxxxxx" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Alamat</label>
                                <textarea class="form-control" name="address" rows="3" placeholder="Masukkan alamat" required></textarea>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Upload KTP (dummy)</label>
                                <input type="file" class="form-control" name="ktp" accept="image/*,.pdf">

                                <div class="form-text">(Placeholder upload KTP - belum diimplementasikan)</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Upload Foto Diri (dummy)</label>
                                <input type="file" class="form-control" name="selfie" accept="image/*,.pdf">

                                <div class="form-text">(Placeholder upload foto - belum diimplementasikan)</div>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-2 align-items-md-center justify-content-between mt-4">
                            <button type="submit" class="btn btn-primary px-4">Daftar Membership</button>

                            <div class="text-muted">
                                Sudah punya akun? 
                                <a href="{{ url('/login') }}" class="text-decoration-none">Masuk</a>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4 mb-0" role="alert">
                            Form ini hanya halaman registrasi membership (tanpa proses submit pada tahap ini).
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-muted small mt-3">
                Dengan mendaftar, Anda setuju untuk ketentuan membership.
            </div>
        </div>
    </div>
</div>
@endsection

