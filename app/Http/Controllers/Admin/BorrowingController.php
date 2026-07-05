<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Borrowing::query()->with(['book', 'user'])->orderByDesc('borrow_date');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $borrowings = $query->paginate(15);

        return view('admin.borrowings.index', [
            'borrowings' => $borrowings,
            'users' => User::all(),
            'books' => Book::all(),
        ]);
    }

    public function create(): View
    {
        return view('admin.borrowings.create', [
            'books' => Book::all(),
            'users' => User::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'book_id' => ['required', 'integer', 'exists:books,id'],
            'borrow_date' => ['required', 'date'],
            'return_date' => ['nullable', 'date'],
            'status' => ['required', 'string', 'in:dipinjam,dikembalikan'],
        ]);

        Borrowing::create($data);

        return redirect()->route('admin.borrowings.index')->with('success', 'Borrowing created');
    }

    public function edit(Borrowing $borrowing): View
    {
        return view('admin.borrowings.edit', [
            'borrowing' => $borrowing,
            'books' => Book::all(),
            'users' => User::all(),
        ]);
    }

    public function update(Request $request, Borrowing $borrowing): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'book_id' => ['required', 'integer', 'exists:books,id'],
            'borrow_date' => ['required', 'date'],
            'return_date' => ['nullable', 'date'],
            'status' => ['required', 'string', 'in:dipinjam,dikembalikan'],
        ]);

        $borrowing->update($data);

        return redirect()->route('admin.borrowings.index')->with('success', 'Borrowing updated');
    }

    public function destroy(Borrowing $borrowing): RedirectResponse
    {
        $borrowing->delete();

        return redirect()->route('admin.borrowings.index')->with('success', 'Borrowing deleted');
    }
}

