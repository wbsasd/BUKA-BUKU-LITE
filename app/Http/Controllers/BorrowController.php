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

        // Check if this is an extension payment
        $extendingBorrowingId = session('extending_borrowing_id');

        if ($extendingBorrowingId) {
            // This is an extension payment
            $originalBorrowing = Borrowing::findOrFail($extendingBorrowingId);

            // Update the extension record
            $borrowing->payment_method = $data['payment_method'];
            $borrowing->status = 'paid';
            $borrowing->borrowed_at = now();
            $borrowing->due_date = now()->addDays($borrowing->duration);
            $borrowing->borrow_date = $borrowing->borrowed_at;
            $borrowing->return_date = $borrowing->due_date;
            $borrowing->save();

            // Update the original borrowing
            $originalBorrowing->due_date = $borrowing->due_date;
            $originalBorrowing->duration = $originalBorrowing->duration + $borrowing->duration;
            $originalBorrowing->warning_sent = false;
            $originalBorrowing->warning_sent_at = null;
            $originalBorrowing->save();

            // Clear session
            session()->forget('extending_borrowing_id');

            // Delete the extension record (we'll just keep it for record, actually let's keep it)
            // Or we can use it for tracking purposes

            return redirect()->route('borrow.finish', $borrowing);
        }

        // Normal payment (new borrowing)
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

    public function returnBook(Request $request, Borrowing $borrowing)
    {
        // Validate ownership
        abort_if($borrowing->user_id !== Auth::id(), 403);

        // Validate status is paid
        if ($borrowing->status !== 'paid') {
            return redirect()->route('borrow.history')
                ->with('error', 'Buku sudah dikembalikan atau status tidak valid');
        }

        // Update borrowing
        $borrowing->status = 'returned';
        $borrowing->returned_at = now();
        $borrowing->save();

        // Increase book stock
        $borrowing->book->increment('stock');

        return redirect()->route('borrow.history')
            ->with('success', 'Buku berhasil dikembalikan');
    }

    public function extendBook(Request $request, Borrowing $borrowing)
    {
        // Validate ownership
        abort_if($borrowing->user_id !== Auth::id(), 403);

        // Validate status is paid (can only extend active borrowing)
        if ($borrowing->status !== 'paid') {
            return redirect()->route('borrow.history')
                ->with('error', 'Hanya buku yang masih dipinjam yang bisa diperpanjang');
        }

        $data = $request->validate([
            'extend_duration' => ['required', 'in:3,7,14,30'],
        ]);

        $prices = [
            3 => 10000,
            7 => 20000,
            14 => 35000,
            30 => 60000,
        ];

        $duration = (int) $data['extend_duration'];
        $price = $prices[$duration] ?? 0;

        // Create a new borrowing record for the extension
        $extension = Borrowing::create([
            'user_id' => Auth::id(),
            'book_id' => $borrowing->book_id,
            'duration' => $duration,
            'price' => $price,
            'status' => 'pending',
            // Mark as extension by storing original borrowing ID in a session
        ]);

        // Store the extension info in session for payment success handling
        session(['extending_borrowing_id' => $borrowing->id]);

        return redirect()->route('borrow.payment', $extension);
    }
}

