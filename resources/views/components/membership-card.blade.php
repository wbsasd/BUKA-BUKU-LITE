@props(['role' => 'Pengguna', 'name' => '', 'expires' => null])

<div class="card text-center shadow-sm">
  <div class="card-body">
    <h6 class="card-subtitle mb-2 text-muted">{{ $role }}</h6>
    <h5 class="card-title">{{ $name }}</h5>
    @if($expires)
      <p class="card-text small text-muted">Berlaku sampai {{ $expires }}</p>
    @endif
    <div>
      {{ $slot }}
    </div>
  </div>
</div>
