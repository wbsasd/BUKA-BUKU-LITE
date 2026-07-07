<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowController extends Controller
{
    public function booking(Book $book)
    {
        return view('borrowings.booking', ['book' => $book]);
    }

    public function storeBooking(Request $request, Book $book)
    {
        $data = $request->validate([
            'duration' => ['required', 'in:3,7,14,30'],
        ]);

        $prices = [
            3 => 10000,
            7 => 20000,
            14 => 35000,
            30 => 60000,
        ];

        $duration = (int) $data['duration'];
        $price = $prices[$duration] ?? 0;

        $borrowing = Borrowing::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'duration' => $duration,
            'price' => $price,
            'status' => 'pending',
        ]);

        return redirect()->route('borrow.payment', $borrowing);
    }

    public function payment(Borrowing $borrowing)
    {
        abort_if($borrowing->user_id !== Auth::id(), 403);

        return view('borrowings.payment', ['borrowing' => $borrowing]);
    }

    public function pay(Request $request, Borrowing $borrowing)
    {
        abort_if($borrowing->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'payment_method' => ['required', 'string'],
        ]);

        $borrowing->payment_method = $data['payment_method'];
        $borrowing->status = 'paid';
        $borrowing->borrowed_at = now();
        $borrowing->due_date = now()->addDays($borrowing->duration);
        $borrowing->borrow_date = $borrowing->borrowed_at;
        $borrowing->return_date = $borrowing->due_date;
        $borrowing->save();

        return redirect()->route('borrow.finish', $borrowing);
    }

    public function finish(Borrowing $borrowing)
    {
        abort_if($borrowing->user_id !== Auth::id(), 403);

        return view('borrowings.finish', ['borrowing' => $borrowing]);
    }

    public function history()
    {
        $borrowings = Borrowing::with('book')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('borrowings.history', ['borrowings' => $borrowings]);
    }
}
