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
                                        @if((int) $book->stock > 0)
                                            <span class="badge bg-light text-dark">Stok: <strong>{{ $book->stock }}</strong> buku</span>
                                        @else
                                            <span class="badge bg-light text-dark">Stok Habis</span>
                                        @endif
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
                                        <div class="star-icons text-warning fs-5" aria-label="average rating">
                                            @php
                                                $avg = $avgRating ?? 0;
                                                $fullStars = (int) floor($avg);
                                                $hasHalf = ($avg - $fullStars) >= 0.5;
                                            @endphp

                                            @for($i = 0; $i < 5; $i++)
                                                @if($i < $fullStars)
                                                    <i class="bi bi-star-fill"></i>
                                                @elseif($i === $fullStars && $hasHalf)
                                                    <i class="bi bi-star-half"></i>
                                                @else
                                                    <i class="bi bi-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <div>
                                            <div class="h4 mb-1">{{ $avgRating ? number_format($avgRating, 1) : '0.0' }}</div>
                                            <div class="text-muted">{{ $reviewsCount }} ulasan</div>
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
                                                <a href="#" class="btn btn-primary" onclick="alert('Menunggu persetujuan admin.'); return false;">Pinjam Buku</a>
                                            @elseif($membershipStatus === 'rejected')
                                                <a href="#" class="btn btn-primary" onclick="alert('Permintaan membership Anda ditolak.'); return false;">Pinjam Buku</a>
                                            @else
                                                @if((int) $book->stock > 0)
                                                    <a href="{{ route('borrow.booking', $book) }}" class="btn btn-primary">Pinjam Buku</a>
                                                @else
                                                    <a href="#" class="btn btn-secondary" aria-disabled="true" onclick="return false;">Stok Habis</a>
                                                @endif
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

                    <div class="mb-4">
                        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                            <div class="d-flex align-items-center gap-3">
                                <div class="text-warning" style="font-size: 1.1rem;">
                                    @php
                                        $avg = $avgRating ?? 0;
                                        $fullStars = (int) floor($avg);
                                        $hasHalf = ($avg - $fullStars) >= 0.5;
                                    @endphp
                                    @for($i = 0; $i < 5; $i++)
                                        @if($i < $fullStars)
                                            <i class="bi bi-star-fill"></i>
                                        @elseif($i === $fullStars && $hasHalf)
                                            <i class="bi bi-star-half"></i>
                                        @else
                                            <i class="bi bi-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div>
                                    <div class="h4 mb-0">{{ $avgRating ? number_format($avgRating, 1) : '0.0' }}</div>
                                    <div class="text-muted">{{ $reviewsCount }} ulasan</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @auth
                        <div class="mb-4">
                            <div class="card review-card border-0 shadow-sm">
                                <div class="card-body">
                                    @if($myReview)
                                        <form method="POST" action="{{ route('book.reviews.update', $myReview->id) }}">
                                            @csrf
                                            @method('PUT')
                                    @else
                                        <form method="POST" action="{{ route('book.reviews.store', $book->id) }}">
                                            @csrf
                                    @endif

                                            <div class="mb-3 rating-input-block">
                                                <label class="form-label">Rating</label>

                                                <div class="rating-stars-wrapper" data-rating-initial="{{ (int) old('rating', $myReview?->rating) ?: 0 }}">
                                                    {{-- Hidden input tetap untuk backend menerima integer 1-5 --}}
                                                    <input
                                                        type="hidden"
                                                        name="rating"
                                                        id="rating"
                                                        value="{{ (int) old('rating', $myReview?->rating) ?: 0 }}"
                                                    >

                                                    <div class="rating-stars" role="radiogroup" aria-label="Pilih rating buku">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <button
                                                                type="button"
                                                                class="rating-star"
                                                                data-value="{{ $i }}"
                                                                aria-label="{{ $i }}"
                                                            >
                                                                <i class="bi bi-star-fill" aria-hidden="true"></i>
                                                            </button>
                                                        @endfor
                                                    </div>

                                                    <div class="rating-label text-muted mt-2" id="rating-label">&nbsp;</div>
                                                </div>

                                                <div class="rating-front-validation text-danger small mt-1" id="rating-front-validation" style="display:none;">
                                                    Silakan pilih rating terlebih dahulu.
                                                </div>

                                                @error('rating')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="review" class="form-label">Ulasan</label>
                                                <textarea
                                                    id="review"
                                                    name="review"
                                                    class="form-control"
                                                    rows="4"
                                                >{{ old('review', $myReview?->review) }}</textarea>
                                                @error('review')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary">
                                                    {{ $myReview ? 'Simpan Perubahan' : 'Kirim Ulasan' }}
                                                </button>
                                            </div>
                                        </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mb-4">
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                Masuk untuk memberi ulasan
                            </a>
                        </div>
                    @endauth

                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        @forelse($reviews as $review)
                            @php
                                $isOwner = auth()->check() && auth()->id() === $review->user_id;
                                $userName = $review->user?->name ?? 'Pengguna';
                            @endphp
                            <div class="col">
                                <div class="card review-card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center gap-3 mb-3">
                                            <div class="review-avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center overflow-hidden">
                                                @if($review->user?->avatar)
                                                    <img src="{{ asset('storage/'.$review->user->avatar) }}" alt="{{ $review->user->name }}" style="width:100%;height:100%;object-fit:cover;" />
                                                @else
                                                    {{ strtoupper(substr($review->user?->name ?? 'U', 0, 1)) }}
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $review->user?->name ?? 'Pengguna' }}</h6>
                                                <div class="text-warning small">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bi bi-star-fill" style="opacity: {{ $review->rating >= $i ? 1 : 0.3 }};"></i>
                                                    @endfor
                                                </div>
                                                <div class="text-muted small mt-1">{{ $review->created_at?->diffForHumans() }}</div>
                                            </div>

                                            @if($isOwner)
                                                <div class="ms-2 d-flex flex-column gap-2 align-items-end">
                                                    <a href="#" class="btn btn-sm btn-outline-primary" onclick="window.scrollTo({top:0,behavior:'smooth'}); return false;">Edit</a>
                                                    <form method="POST" action="{{ route('book.reviews.destroy', $review->id) }}" onsubmit="return confirm('Hapus ulasan?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>

                                        <p class="text-muted mb-0">{{ $review->review }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-muted">Belum ada ulasan untuk buku ini.</div>
                            </div>
                        @endforelse
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

/* ===== Interaktif Rating (UI saja) ===== */
.rating-stars-wrapper {
    --star-size: 2.2rem;
}

.rating-stars {
    display: inline-flex;
    gap: 6px;
    align-items: center;
    user-select: none;
}

.rating-star {
    width: var(--star-size);
    height: var(--star-size);
    padding: 0;
    border: none;
    background: transparent;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transform: scale(1);
    transition: transform 160ms ease, color 160ms ease, filter 160ms ease;
}

.rating-star i {
    font-size: var(--star-size);
    transition: transform 160ms ease, opacity 160ms ease, color 160ms ease;
}

.rating-star:hover {
    transform: scale(1.08);
}

.rating-star.is-active i {
    color: #f5c400; /* kuning */
    opacity: 1;
    filter: drop-shadow(0 4px 10px rgba(245, 196, 0, 0.25));
}

.rating-star:not(.is-active) i {
    color: #b0b0b0; /* abu-abu */
    opacity: 1;
}

.rating-front-validation {
    min-height: 18px;
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

@push('scripts')
<script>
(function () {
    const wrapper = document.querySelector('.rating-stars-wrapper');
    if (!wrapper) return;

    const ratingInput = wrapper.querySelector('#rating');
    const stars = Array.from(wrapper.querySelectorAll('.rating-star'));
    const labelEl = wrapper.querySelector('#rating-label');
    const validationEl = wrapper.querySelector('#rating-front-validation');

    const labels = {
        1: 'Sangat Buruk',
        2: 'Buruk',
        3: 'Cukup',
        4: 'Bagus',
        5: 'Sangat Bagus'
    };

    function setActive(value, mode) {
        const v = Number(value || 0);

        stars.forEach(function (btn) {
            const starValue = Number(btn.dataset.value);
            btn.classList.toggle('is-active', starValue <= v);
        });

        if (labelEl) {
            labelEl.textContent = v >= 1 ? (labels[v] || '') : '';
        }

        if (mode === 'click') {
            if (ratingInput) ratingInput.value = String(v);
        }
    }

    function hideValidation() {
        if (validationEl) validationEl.style.display = 'none';
    }

    // Init (edit mode / old input)
    const initial = Number(wrapper.dataset.ratingInitial || 0);
    setActive(initial, 'init');

    // Hover
    stars.forEach(function (btn) {
        btn.addEventListener('mouseenter', function () {
            const hoverVal = Number(btn.dataset.value);
            hideValidation();
            setActive(hoverVal, 'hover');
        });

        btn.addEventListener('mouseleave', function () {
            const current = Number(ratingInput?.value || 0);
            setActive(current, 'leave');
        });

        // Click
        btn.addEventListener('click', function () {
            const clickVal = Number(btn.dataset.value);
            hideValidation();
            setActive(clickVal, 'click');
        });
    });

    // Front-end validation on submit (jangan ubah backend)
    const form = wrapper.closest('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            const current = Number(ratingInput?.value || 0);
            if (!current) {
                e.preventDefault();
                if (validationEl) validationEl.style.display = 'block';
            }
        });
    }
})();
</script>
@endpush

