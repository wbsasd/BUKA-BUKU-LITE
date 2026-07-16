@extends('layouts.admin')

@section('admin.content')
  <div class="card">
    <div class="card-body">

      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h5 class="card-title mb-1">Permintaan Registrasi</h5>
          <p class="text-muted small mb-0">Kelola permintaan membership yang menunggu persetujuan admin.</p>
        </div>
      </div>

      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @if($pendingUsers->isEmpty())
        <div class="alert alert-info mb-0">Tidak ada permintaan registrasi saat ini.</div>
      @else
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Tanggal Daftar</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($pendingUsers as $user)
                @php
                  $badgeClass = match($user->membership_status) {
                      'active' => 'badge-success',
                      'rejected' => 'badge-danger',
                      default => 'badge-warning'
                  };
                @endphp
                <tr>
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>{{ ucfirst($user->role ?? 'user') }}</td>
                  <td>{{ $user->created_at?->format('Y-m-d H:i') }}</td>
                  <td>
                    <span class="badge {{ $badgeClass }}">{{ ucfirst($user->membership_status ?? 'pending') }}</span>
                  </td>
                  <td>
                    <form method="POST" action="{{ route('admin.membership-requests.approve', $user) }}" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-success">Approve</button>
                    </form>

                    <form method="POST" action="{{ route('admin.membership-requests.reject', $user) }}" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-danger">Reject</button>
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
@endsection

