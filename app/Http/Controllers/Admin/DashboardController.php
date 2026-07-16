<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\MembershipUpgrade;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks = Book::query()->count();

        // "Aktif" = bukan returned (mengikuti requirement)
        $activeBorrowingsQuery = Borrowing::query()
            ->where('status', '!=', 'returned');

        $totalBorrowed = (clone $activeBorrowingsQuery)->count();

        $today = now()->toDateString();
        $totalOverdue = (clone $activeBorrowingsQuery)
            ->whereDate('due_date', '<', $today)
            ->count();

        $membershipPendingCount = MembershipUpgrade::query()
            ->where('status', 'pending')
            ->count();

        $totalUsers = User::query()
            ->where('role', '!=', 'admin')
            ->count();

        // Data tabel di dashboard
        $recentBorrowings = Borrowing::query()
            ->where('status', '!=', 'returned')
            ->with(['user', 'book'])
            ->latest('borrowed_at')
            ->take(5)
            ->get();

        $recentMembershipRequests = MembershipUpgrade::query()
            ->with('user')
            ->latest('requested_at')
            ->take(5)
            ->get();

        // Untuk variabel yang ada di blade, pastikan tidak undefined
        $lowStockBooks = [];

        return view('admin.dashboard', [
            'totalBooks' => $totalBooks,
            'totalBorrowed' => $totalBorrowed,
            'totalOverdue' => $totalOverdue,
            'membershipPendingCount' => $membershipPendingCount,
            'totalUsers' => $totalUsers,
            'recentBorrowings' => $recentBorrowings,
            'recentMembershipRequests' => $recentMembershipRequests,
            'lowStockBooks' => $lowStockBooks,
        ]);
    }
}

