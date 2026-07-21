<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BookReview;
use App\Models\Borrowing;
use App\Models\MembershipUpgrade;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardStatsRealtimeController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $borrowingCount = Borrowing::query()
            ->where('user_id', $user->id)
            ->where('status', '!=', 'returned')
            ->count();

        // Total denda: pakai fine accessor dari model Borrowing tanpa mengubah business logic yang sudah berjalan.
        $totalDenda = Borrowing::query()
            ->where('user_id', $user->id)
            ->where('status', '!=', 'returned')
            ->get()
            ->sum(function (Borrowing $borrowing) {
                /** @var int $fine */
                $fine = $borrowing->fine;
                return (int) $fine;
            });

        $booksReadCount = BookReview::query()
            ->where('user_id', $user->id)
            ->count();

        // Membership label mapping:
        // - active => Premium
        // - pending => Menunggu Approval
        // - none => Basic Member
        // Menggunakan sumber membership_upgrades dan end_date untuk menentukan expired/active.
        $latestUpgrade = MembershipUpgrade::query()
            ->where('user_id', $user->id)
            ->orderByDesc('approved_at')
            ->orderByDesc('requested_at')
            ->first();

        $membershipLabel = 'Basic Member';

        if ($latestUpgrade) {
            // Pending request
            if (($latestUpgrade->status ?? null) === 'pending') {
                $membershipLabel = 'Menunggu Approval';
            } else {
                // Approved membership => check end_date for active/expired
                if (($latestUpgrade->status ?? null) === 'approved' && $latestUpgrade->end_date) {
                    $end = Carbon::parse($latestUpgrade->end_date)->startOfDay();

                    if ($end->gte(now()->startOfDay())) {
                        $membershipLabel = 'Premium';
                    } else {
                        // expired treated as Basic Member per requirement
                        $membershipLabel = 'Basic Member';
                    }
                }
            }
        }

        return response()->json([
            'borrowingCount' => $borrowingCount,
            'membershipLabel' => $membershipLabel,
            'totalDenda' => $totalDenda,
            'booksReadCount' => $booksReadCount,
        ]);
    }
}

