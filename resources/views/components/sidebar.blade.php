@props(['title' => config('app.name', 'Buka Buku')])

<aside class="d-flex flex-column bg-white border-end vh-100 p-3">
  <div class="mb-4">
    <h5 class="mb-0">{{ $title }}</h5>
  </div>
  <nav class="nav nav-pills flex-column">
    {{ $slot }}
  </nav>
  <div class="mt-auto">
    {{ $footer ?? '' }}
  </div>
</aside>
