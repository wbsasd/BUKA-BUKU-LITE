@props(['bookId' => null, 'title' => 'Judul Buku', 'author' => '', 'cover' => null, 'year' => null, 'rating' => null])

<div class="card h-100 shadow-sm">
  @if($cover)
    @if($bookId)
      <a href="{{ route('book.detail', ['id' => $bookId]) }}" class="d-block">
        <img src="{{ $cover }}" class="card-img-top" alt="{{ $title }}">
      </a>
    @else
      <img src="{{ $cover }}" class="card-img-top" alt="{{ $title }}">
    @endif
  @else
    <div class="ratio ratio-4x3 bg-light d-flex align-items-center justify-content-center">
      <i class="bi bi-book-half fs-1 text-secondary"></i>
    </div>
  @endif
  <div class="card-body d-flex flex-column">
    <h6 class="card-title mb-1 text-truncate">{{ $title }}</h6>
    <p class="text-muted mb-2 small">{{ $author }} @if($year) · {{ $year }}@endif</p>
    @if($rating)
      <div class="mb-2 text-warning">
        @for($i=0;$i<intval($rating);$i++)
          <i class="bi bi-star-fill"></i>
        @endfor
      </div>
    @endif
    <div class="mt-auto">
      {{ $slot }}
    </div>
  </div>
</div>
