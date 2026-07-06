@extends('layouts.app')

@section('content')
@php
    use App\Models\Book;

    $relatedBooks = collect();

    if ($book->category) {
        $relatedBooks = $book->category->books()
            ->where('id', '!=', $book->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();
    }

    if ($relatedBooks->isEmpty()) {
        $relatedBooks = Book::where('id', '!=', $book->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();
    }
@endphp

<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card book-detail-card shadow-sm border-0 overflow-hidden mb-4">
                <div class="row g-0 align-items-center">
                    <div class="col-12 col-lg-4">
                        <div class="book-cover-wrapper d-flex align-items-center justify-content-center bg-light p-4">
                            <img
                                src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('images/placeholder-cover.png') }}"
                                alt="{{ $book->title }}"
                                class="book-cover rounded shadow-sm"
                            >
                        </div>
                    </div>
                    <div class="col-12 col-lg-8">
                        <div class="card-body p-4">
                            <div class="d-flex flex-column h-100 gap-3">
                                <div>
                                    <span class="badge bg-primary-soft text-primary mb-2">Detail Buku</span>
                                    <h1 class="book-title mb-2">{{ $book->title }}</h1>
                                    <p class="book-author text-secondary mb-2">{{ $book->author }}</p>
                                    <p class="text-muted mb-2">
                                        {{ $book->publisher }} · {{ $book->publication_year }}
                                    </p>
                                    <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                                        <span class="badge bg-secondary">Kategori: {{ $book->category?->name ?? 'Umum' }}</span>
                                        <span class="badge bg-light text-dark">Stock: <strong>{{ $book->stock }}</strong></span>
                                    </div>
                                </div>

                                @if(trim($book->description ?? ''))
                                    <div class="book-synopsis">
                                        <h5 class="mb-3">Sinopsis</h5>
                                        <p class="text-muted lh-lg mb-0">{{ $book->description }}</p>
                                    </div>
                                @endif

                                <div class="rating-summary d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="star-icons text-warning fs-5">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-half"></i>
                                        </div>
                                        <div>
                                            <div class="h4 mb-1">4.8</div>
                                            <div class="text-muted">200 ulasan</div>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2">
@guest
                                            <a href="{{ route('membership.register') }}" class="btn btn-primary">Pinjam Buku</a>
                                        @else
                                            @php
                                                $membershipStatus = auth()->user()?->membership_status;
                                            @endphp

                                            @if($membershipStatus === 'pending')
                                                <a href="#" class="btn btn-primary" onclick="alert('Permintaan membership Anda masih menunggu persetujuan Admin.'); return false;">Pinjam Buku</a>
                                            @elseif($membershipStatus === 'rejected')
                                                <a href="#" class="btn btn-primary" onclick="alert('Permintaan membership Anda ditolak.'); return false;">Pinjam Buku</a>
                                            @else
                                                <a href="{{ $book->stock > 0 ? route('reader', ['id' => $book->id]) : '#' }}" class="btn btn-primary">Pinjam Buku</a>
                                            @endif
                                        @endguest

                                        <a href="{{ $book->stock > 0 ? route('reader', ['id' => $book->id]) : '#' }}" class="btn btn-outline-primary">Baca Sekarang</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="mb-4">Rating & Ulasan</h5>
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        @for($i = 1; $i <= 3; $i++)
                            <div class="col">
                                <div class="card review-card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center gap-3 mb-3">
                                            <div class="review-avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center">
                                                {{ strtoupper(substr('U'.$i, 0, 1)) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Pengguna {{ $i }}</h6>
                                                <div class="text-warning small">
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-muted mb-0">Ulasan singkat tentang buku ini. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="mb-0">Buku Terkait</h5>
                        <span class="text-muted">Pilihan untuk Anda</span>
                    </div>

                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3">
                        @foreach($relatedBooks as $related)
                            <div class="col">
                                <div class="related-book-card h-100 border-0 shadow-sm">
                                    <div class="related-book-cover">
                                        <img
                                            src="{{ $related->cover_image ? asset('storage/'.$related->cover_image) : asset('images/placeholder-cover.png') }}"
                                            alt="{{ $related->title }}"
                                        >
                                    </div>

                                    <div class="p-3 d-flex flex-column gap-2">
                                        <h6 class="related-book-title mb-0">{{ $related->title }}</h6>
                                        <p class="related-book-author text-muted mb-0">{{ $related->author }}</p>
                                        <div class="related-book-category small text-secondary">
                                            Kategori: {{ $related->category?->name ?? 'Umum' }}
                                        </div>

                                        <div class="rating-stars text-warning small" aria-label="rating">
                                            @for($i = 0; $i < 5; $i++)
                                                <i class="bi bi-star-fill"></i>
                                            @endfor
                                        </div>

                                        <div class="mt-auto pt-1">
                                            <a
                                                href="{{ route('book.detail', ['id' => $related->id]) }}"
                                                class="btn btn-primary w-100 related-book-btn"
                                            >
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.book-detail-card {
    border-radius: 1rem;
}
.book-cover-wrapper {
    min-height: 100%;
}
.book-cover {
    width: 100%;
    max-width: 300px;
    height: auto;
    object-fit: cover;
}
.book-title {
    font-size: clamp(1.75rem, 2.5vw, 2.5rem);
    font-weight: 700;
}
.book-author {
    font-size: 1rem;
}
.badge.bg-primary-soft {
    background-color: rgba(13, 110, 253, 0.12);
    color: #0d6efd;
}
.review-avatar {
    width: 48px;
    height: 48px;
    font-weight: 700;
}
.review-card {
    border-radius: 1rem;
}
.related-book-card {
    border-radius: 12px;
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
    transition: 0.3s;
    overflow: hidden;
    padding-bottom: 15px;
    background: #fff;
}

.related-book-cover {
    padding: 0;
}

.related-book-cover img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-radius: 10px;
    display: block;
}

.related-book-title {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.related-book-author {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.related-book-btn {
    border-radius: 10px;
}

.related-book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
}

@media (max-width: 991.98px) {
    .book-detail-card .card-body {
        padding: 1.5rem;
    }
}
</style>
@endpush
