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

        // Statistic Chart: Borrowings per month (Jan–Dec) for current year
        // NOTE: No filter status (all borrowings included)
        $currentYear = now()->year;

        $chartLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $chartValues = array_fill(0, 12, 0);

        $borrowingsByMonth = Borrowing::query()
            ->selectRaw('MONTH(borrowed_at) as month_number, COUNT(*) as total')
            ->whereYear('borrowed_at', $currentYear)
            ->groupByRaw('MONTH(borrowed_at)')
            ->pluck('total', 'month_number');

        foreach ($borrowingsByMonth as $monthNumber => $total) {
            // monthNumber: 1..12
            $idx = ((int) $monthNumber) - 1;
            if ($idx >= 0 && $idx < 12) {
                $chartValues[$idx] = (int) $total;
            }
        }


        // Untuk variabel yang ada di blade, pastikan tidak undefined
        $lowStockBooks = [];

        // Recent Activity (merged timeline)
        $recentActivities = collect();

        // 1) Registrasi baru (user non-admin)
        $recentUsers = User::query()
            ->where('role', '!=', 'admin')
            ->latest('created_at')
            ->take(200)
            ->get(['id', 'name', 'created_at', 'role']);

        foreach ($recentUsers as $u) {
            $recentActivities->push([
                'title' => 'User baru registrasi',
                'description' => $u->name ? "{$u->name} terdaftar" : 'Registrasi baru',
                'created_at' => $u->created_at,
                'icon' => 'bi bi-person-plus',
                'type' => 'registration',
            ]);
        }

        // 2) Peminjaman (borrowed_at)
        $recentBorrowingCreated = Borrowing::query()
            ->where('status', '!=', 'returned')
            ->latest('borrowed_at')
            ->take(200)
            ->with(['user', 'book'])
            ->get();

        foreach ($recentBorrowingCreated as $rb) {
            $uName = $rb->user?->name ?? '-';
            $bTitle = $rb->book?->title ?? '-';

            $recentActivities->push([
                'title' => 'User meminjam buku',
                'description' => "{$uName} meminjam: {$bTitle}",
                'created_at' => $rb->borrowed_at,
                'icon' => 'bi bi-book',
                'type' => 'borrowing',
            ]);
        }

        // 3) Pengembalian (returned_at)
        $recentBorrowingReturned = Borrowing::query()
            ->where('status', 'returned')
            ->whereNotNull('returned_at')
            ->latest('returned_at')
            ->take(200)
            ->with(['user', 'book'])
            ->get();

        foreach ($recentBorrowingReturned as $rb) {
            $uName = $rb->user?->name ?? '-';
            $bTitle = $rb->book?->title ?? '-';

            $recentActivities->push([
                'title' => 'Buku dikembalikan',
                'description' => "{$uName} mengembalikan: {$bTitle}",
                'created_at' => $rb->returned_at,
                'icon' => 'bi bi-arrow-repeat',
                'type' => 'return',
            ]);
        }

        // 4) Membership upgrade approved (approved_at)
        $recentMembershipApproved = MembershipUpgrade::query()
            ->where('status', 'approved')
            ->whereNotNull('approved_at')
            ->latest('approved_at')
            ->take(200)
            ->with(['user'])
            ->get();

        foreach ($recentMembershipApproved as $mu) {
            $uName = $mu->user?->name ?? '-';

            $planKey = MembershipUpgrade::planKey((int) ($mu->months ?? 0));
            $planLabel = $mu->months ? $planKey : ($mu->plan_name ?? 'Paket');

            $recentActivities->push([
                'title' => 'Membership disetujui',
                'description' => "{$uName} upgrade membership: {$planLabel}",
                'created_at' => $mu->approved_at,
                'icon' => 'bi bi-crown',
                'type' => 'membership_upgrade',
            ]);
        }

        $recentActivities = $recentActivities
            ->sortByDesc(function ($a) {
                return $a['created_at'];
            })
            ->take(10)
            ->values();

        return view('admin.dashboard', [
            'totalBooks' => $totalBooks,
            'totalBorrowed' => $totalBorrowed,
            'totalOverdue' => $totalOverdue,
            'membershipPendingCount' => $membershipPendingCount,
            'totalUsers' => $totalUsers,
            'recentBorrowings' => $recentBorrowings,
            'recentMembershipRequests' => $recentMembershipRequests,
            'lowStockBooks' => $lowStockBooks,
            'chartLabels' => $chartLabels,
            'chartValues' => $chartValues,
            'recentActivities' => $recentActivities,
        ]);


    }
}

