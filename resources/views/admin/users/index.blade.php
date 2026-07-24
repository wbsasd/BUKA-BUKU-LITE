@extends('layouts.admin')

@section('admin.content')
  <div class="card">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="card-title mb-0">Users</h5>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">Tambah User</a>
      </div>

      <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Membership</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
            <tr>
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>{{ $user->role }}</td>
              <td>{{ $user->membership ?? '-' }}</td>
              <td>{{ $user->status ?? 'active' }}</td>
              <td>
                <div class="d-flex flex-wrap gap-1">
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">Edit</a>

                <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#resetPasswordModal{{ $user->id }}">
                  Reset Password
                </button>

                <form action="{{ route('admin.users.reset-password', $user) }}" method="POST" class="d-inline">
                  @csrf
                  <div class="modal fade" id="resetPasswordModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h6 class="modal-title">Reset Password</h6>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <input type="hidden" name="user_id" value="{{ $user->id }}">
                          <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                          </div>
                          <div class="mb-2">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required>
                            @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                          <button type="submit" class="btn btn-warning">Simpan</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>

                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      </div>

      {{ $users->links() }}
    </div>
  </div>
@endsection
