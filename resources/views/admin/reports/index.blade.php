@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Laporan Admin</span>
                </div>

                <div class="card-body">
                    <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-2 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="" selected>Semua</option>
                                <option value="dipinjam" @selected(($filters['status'] ?? '')==='dipinjam')>dipinjam</option>
                                <option value="dikembalikan" @selected(($filters['status'] ?? '')==='dikembalikan')>dikembalikan</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button class="btn btn-primary w-100">Tampilkan</button>
                        </div>
                    </form>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="alert alert-info mb-0">Total Buku: <b>{{ $booksTotal }}</b></div>
                        </div>
                        <div class="col-md-3">
                            <div class="alert alert-info mb-0">Total Pengguna: <b>{{ $usersTotal }}</b></div>
                        </div>
                        <div class="col-md-3">
                            <div class="alert alert-secondary mb-0">Dipinjam: <b>{{ $borrowingsDipinjamCount }}</b></div>
                        </div>
                        <div class="col-md-3">
                            <div class="alert alert-success mb-0">Pengembalian: <b>{{ $pengembalianCount }}</b></div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mb-3">
                        <a class="btn btn-outline-danger" href="{{ route('admin.reports.export.pdf', $filters) }}">Export PDF</a>
                        <a class="btn btn-outline-success" href="{{ route('admin.reports.export.excel', $filters) }}">Export Excel</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Buku</th>
                                    <th>Pengguna</th>
                                    <th>Tanggal Peminjaman</th>
                                    <th>Tanggal Pengembalian</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($borrowings as $b)
                                    <tr>
                                        <td>{{ $b->id }}</td>
                                        <td>{{ $b->book?->title }}</td>
                                        <td>{{ $b->user?->name }}</td>
                                        <td>{{ optional($b->borrow_date)->format('Y-m-d') }}</td>
                                        <td>
                                            {{ $b->return_date ? optional($b->return_date)->format('Y-m-d') : '-' }}
                                        </td>
                                        <td>{{ $b->status }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

