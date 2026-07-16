<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookDetailController extends Controller
{
    public function show(Request $request, $id)
    {
        $book = Book::with('category')
            ->where('id', $id)
            ->firstOrFail();

        $avgRating = (float) $book->reviews()->avg('rating');
        $reviewsCount = (int) $book->reviews()->count();

        $reviews = $book->reviews()
            ->with('user')
            ->orderByDesc('created_at')
            ->get();

        $myReview = null;
        if (Auth::check()) {
            $myReview = BookReview::where('book_id', $book->id)
                ->where('user_id', Auth::id())
                ->first();
        }

        return view('book-detail', [
            'book' => $book,
            'avgRating' => $avgRating,
            'reviewsCount' => $reviewsCount,
            'reviews' => $reviews,
            'myReview' => $myReview,
        ]);
    }
}


