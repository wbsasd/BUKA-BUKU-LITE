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
        $query = Borrowing::query()->with(['book', 'user'])->orderByDesc('borrowed_at');

        // Search by user name
        if ($request->filled('user_search')) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . request('user_search') . '%');
            });
        }

        // Search by book title
        if ($request->filled('book_search')) {
            $query->whereHas('book', function ($q) {
                $q->where('title', 'like', '%' . request('book_search') . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $statusFilter = (string) $request->string('status');
            if ($statusFilter === 'overdue') {
                // Get overdue borrowings
                $query->where('status', 'paid')->where('due_date', '<', now());
            } else {
                $query->where('status', $statusFilter);
            }
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('borrowed_at', '>=', $request->date('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('borrowed_at', '<=', $request->date('date_to'));
        }

        $borrowings = $query->paginate(15);

        // Calculate dashboard stats
        $today = now()->startOfDay();
        $allBorrowings = Borrowing::with(['book','user'])->get();

        $stats = [
            'dipinjam' => $allBorrowings
                ->where('status', 'paid')
                ->where('due_date', '>=', $today)
                ->count(),
            'jatuh_tempo' => $allBorrowings
                ->where('status', 'paid')
                ->where('due_date', '<', $today)
                ->count(),
            'dikembalikan' => $allBorrowings->where('status', 'returned')->count(),
            'total_denda' => $allBorrowings->sum(fn($b) => $b->fine),
        ];

        return view('admin.borrowings.index', [
            'borrowings' => $borrowings,
            'stats' => $stats,
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
            'status' => ['required', 'string', 'in:paid,returned'],
        ]);

        Borrowing::create($data);

        return redirect()->route('admin.borrowings.index')->with('success', 'Peminjaman berhasil ditambahkan');
    }

    public function edit(Borrowing $borrowing): View
    {
        return view('admin.borrowings.edit', [
            'borrowing' => $borrowing,
            'books' => Book::all(),
            'users' => User::all(),
        ]);
    }

    public function show(Borrowing $borrowing): View
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
            'status' => ['required', 'string', 'in:paid,returned'],
        ]);

        $borrowing->update($data);

        return redirect()->route('admin.borrowings.index')->with('success', 'Peminjaman berhasil diperbarui');
    }

    public function destroy(Borrowing $borrowing): RedirectResponse
    {
        $borrowing->delete();

        return redirect()->route('admin.borrowings.index')->with('success', 'Peminjaman berhasil dihapus');
    }

    /**
     * Send warning to user for overdue books
     */
    public function sendWarning(Borrowing $borrowing): RedirectResponse
    {
        // Only overdue, active borrowings can be warned
        if ($borrowing->status !== 'paid' || now() <= $borrowing->due_date) {
            return redirect()->route('admin.borrowings.index')
                ->with('error', 'Buku ini belum jatuh tempo');
        }

        // If warning already sent, do nothing (still redirect)
        if ($borrowing->warning_sent) {
            return redirect()->route('admin.borrowings.index')
                ->with('success', 'Warning sudah pernah dikirim kepada ' . $borrowing->user->name);
        }

        $borrowing->update([
            'warning_sent' => true,
            'warning_sent_at' => now(),
        ]);

        return redirect()->route('admin.borrowings.index')
            ->with('success', 'Warning berhasil dikirim.');
    }
}


