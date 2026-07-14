@props(['title' => config('app.name', 'Buka Buku')])

<aside class="bb-sidebar bb-desktop-sidebar d-flex flex-column vh-100 p-3">
  <div class="bb-logo-wrap mb-4">
    <div class="d-flex align-items-center gap-3">
      <div class="bb-logo-mark">
        <span style="font-weight:900;color:var(--bb-blue);">BB</span>
      </div>
      <div>
        <div class="fw-bold bb-admin-title">{{ config('app.name','BUKA-BUKU-LITE') }}</div>
        <div class="bb-admin-subtitle small">{{ $title }}</div>
      </div>
    </div>
  </div>

  <nav class="bb-admin-nav d-flex flex-column gap-1">
    {{ $slot }}
  </nav>

  <div class="mt-auto pt-3">
    <div class="small text-muted">Administrator</div>
  </div>
</aside>

