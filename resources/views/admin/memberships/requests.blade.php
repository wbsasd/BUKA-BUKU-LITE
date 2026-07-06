@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0">Permintaan Registrasi</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                @if($pendingUsers->isEmpty())
                    <div class="text-muted">Tidak ada permintaan registrasi.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Tanggal Daftar</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingUsers as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-warning text-dark">{{ $user->membership_status }}</span>
                                        </td>
                                        <td>{{ $user->created_at?->format('Y-m-d H:i') }}</td>
                                        <td class="text-end">
                                            <form method="POST" action="{{ route('admin.membership-requests.approve', $user) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.membership-requests.reject', $user) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

