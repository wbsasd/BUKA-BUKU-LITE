<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['required', 'string', 'min:1'],
        ]);

        BookReview::updateOrCreate(
            [
                'book_id' => $book->id,
                'user_id' => Auth::id(),
            ],
            [
                'rating' => $validated['rating'],
                'review' => $validated['review'],
            ]
        );

        return redirect()->route('book.detail', ['id' => $book->id]);
    }

    public function update(Request $request, BookReview $review)
    {
        abort_unless((int) $review->user_id === (int) Auth::id(), 403);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['required', 'string', 'min:1'],
        ]);

        $review->update([
            'rating' => $validated['rating'],
            'review' => $validated['review'],
        ]);

        return redirect()->route('book.detail', ['id' => $review->book_id]);
    }

    public function destroy(BookReview $review)
    {
        abort_unless((int) $review->user_id === (int) Auth::id(), 403);

        $bookId = $review->book_id;
        $review->delete();

        return redirect()->route('book.detail', ['id' => $bookId]);
    }

}

