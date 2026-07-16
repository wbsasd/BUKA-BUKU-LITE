<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipUpgrade;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;


class AdminMembershipController extends Controller
{
    private const STATUS_PENDING = 'pending';
    // membership_upgrades.status: pending / approved / rejected / expired
    private const STATUS_APPROVED = 'approved';
    private const STATUS_REJECTED = 'rejected';

    private function isColumnExists(string $table, string $column): bool
    {
        return DB::getSchemaBuilder()->hasColumn($table, $column);
    }


    private function computeExpiredQuery(): \Illuminate\Database\Eloquent\Builder
    {
        // Expired: membership_upgrades.status=approved AND membership_upgrades.end_date < today.
        return MembershipUpgrade::query()
            ->where('status', self::STATUS_APPROVED)
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<', Carbon::now()->toDateString());
    }



    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));
        $statusFilter = (string) $request->query('status', 'all');

        $baseQuery = MembershipUpgrade::query()
            ->with(['user'])
            ->orderByDesc('requested_at');

        if ($search !== '') {
            $baseQuery->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('months', 'like', "%{$search}%")
              ->orWhere('payment_method', 'like', "%{$search}%")
              ->orWhere('amount', 'like', "%{$search}%");
        }

        // Filter status (including expired)
        if ($statusFilter !== 'all') {
            if ($statusFilter === 'expired') {
                $expiredIds = $this->computeExpiredQuery()->pluck('id');
                $baseQuery->whereIn('id', $expiredIds);
            } else {
                $baseQuery->where('status', $statusFilter);
            }
        }


        $memberships = $baseQuery->paginate(10)->withQueryString();

        // Stats cards: Pending/Approved/Rejected/Expired
        $pendingCount = MembershipUpgrade::query()->where('status', self::STATUS_PENDING)->count();
        $approvedCount = MembershipUpgrade::query()->where('status', self::STATUS_APPROVED)->count();
        $rejectedCount = MembershipUpgrade::query()->where('status', self::STATUS_REJECTED)->count();
        $expiredCount = $this->computeExpiredQuery()->count();


        return view('admin.memberships.index', [
            'memberships' => $memberships,
            'stats' => [
                'pending' => $pendingCount,
                'approved' => $approvedCount,
                'rejected' => $rejectedCount,
                'expired' => $expiredCount,
            ],
            'q' => $search,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function show(MembershipUpgrade $membership): View
    {
        $membership->loadMissing('user');

        return view('admin.memberships.show', [
            'membership' => $membership,
            'user' => $membership->user,
        ]);
    }

    public function approve(Request $request, MembershipUpgrade $membership): RedirectResponse
    {
        // Approved workflow
        // - membership_upgrades.status = approved
        // - membership_upgrades.approved_at = now()
        // - membership_upgrades.start_date = Carbon::today()
        // - membership_upgrades.end_date = Carbon::today()->addMonthsNoOverflow(duration_bulan)
        // - users.role=premium
        // - users.membership_status=active

        $approvedAt = now();
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addMonthsNoOverflow((int) $membership->months);

        $membership->status = self::STATUS_APPROVED;
        $membership->payment_status = 'paid';
        $membership->approved_at = $approvedAt;
        $membership->rejected_at = null;
        $membership->start_date = $startDate;
        $membership->end_date = $endDate;
        $membership->save();

        // IMPORTANT: Single Source of Truth untuk role/premium hanya melalui admin approve flow:
        //   POST admin/membership-requests/{user}/approve (MembershipApprovalController@approve)
        // Controller ini dibiarkan hanya mengelola membership_upgrades records.
        // Jadi tidak mengubah users.role / users.membership_status di sini.
        // (no-op)

        return redirect()->route('admin.memberships.index')->with('success', 'Membership berhasil di-approve.');
    }



    public function reject(Request $request, MembershipUpgrade $membership): RedirectResponse
    {
        // Reject workflow
        // - membership_upgrades.status = rejected
        // - membership_upgrades.rejected_at = now()
        // - membership_upgrades.start_date/end_date = null
        // - users.role=pengguna
        // - users.membership_status=rejected

        $membership->status = self::STATUS_REJECTED;
        $membership->payment_status = $membership->payment_status ?? 'unpaid';
        $membership->rejected_at = now();
        $membership->approved_at = null;
        $membership->start_date = null;
        $membership->end_date = null;
        $membership->save();

        $user = $membership->user()->firstOrFail();
        $user->role = 'pengguna';
        $user->membership_status = self::STATUS_REJECTED;
        $user->save();

        return redirect()->route('admin.memberships.index')->with('success', 'Membership berhasil di-reject.');
    }


}

