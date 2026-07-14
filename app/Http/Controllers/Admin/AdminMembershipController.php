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
    private const STATUS_ACTIVE = 'active';
    private const STATUS_REJECTED = 'rejected';

    private function isColumnExists(string $table, string $column): bool
    {
        return DB::getSchemaBuilder()->hasColumn($table, $column);
    }

    private function computeExpiredQuery(): \Illuminate\Database\Eloquent\Builder
    {
        // Expired: users start/end columns if exist; otherwise fall back to membership_upgrades.
        // Requirement card uses DB count.
        return MembershipUpgrade::query()->where(function ($q) {
            // If users table has end_date, we can compute expired based on join.
            // But we can't assume columns exist.
            if ($this->isColumnExists('users', 'end_date')) {
                $q->whereHas('user', function ($uq) {
                    $uq->whereNotNull('end_date')
                        ->where('end_date', '<', Carbon::now()->toDateString());
                })->where('status', self::STATUS_ACTIVE);
                return;
            }

            // Fallback: if membership_upgrades has approved_at, treat expired as approved_at older than duration.
            // We don't have duration_month field in schema; use months.
            $q->where('status', self::STATUS_ACTIVE)
                ->whereNotNull('approved_at')
                ->whereRaw('DATE_ADD(approved_at, INTERVAL months MONTH) < ?', [Carbon::now()->toDateString()]);
        });
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
        $approvedCount = MembershipUpgrade::query()->where('status', self::STATUS_ACTIVE)->count();
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
        $membership->status = self::STATUS_ACTIVE;
        $membership->payment_status = 'paid';
        $membership->approved_at = now();
        $membership->rejected_at = null;
        $membership->save();

        $user = $membership->user()->firstOrFail();

        // Update users
        $user->role = 'premium';
        $user->membership_status = self::STATUS_ACTIVE;

        $startDate = now()->toDateString();
        if ($this->isColumnExists('users', 'start_date')) {
            $user->start_date = $startDate;
        }

        if ($this->isColumnExists('users', 'end_date')) {
            // months -> duration_month
            $end = Carbon::parse($startDate)->addMonthsNoOverflow((int) $membership->months)->toDateString();
            $user->end_date = $end;
        }

        $user->save();

        return redirect()->route('admin.memberships.index')->with('success', 'Membership berhasil di-approve.');
    }

    public function reject(Request $request, MembershipUpgrade $membership): RedirectResponse
    {
        $membership->status = self::STATUS_REJECTED;
        $membership->payment_status = $membership->payment_status ?? 'unpaid';
        $membership->rejected_at = now();
        $membership->approved_at = null;
        $membership->save();

        $user = $membership->user()->firstOrFail();
        $user->role = 'pengguna';
        $user->membership_status = self::STATUS_REJECTED;

        // If users has end_date/start_date, we can leave untouched to avoid breaking existing behavior.
        $user->save();

        return redirect()->route('admin.memberships.index')->with('success', 'Membership berhasil di-reject.');
    }
}

