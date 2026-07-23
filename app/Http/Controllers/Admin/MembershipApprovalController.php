<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipUpgrade;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MembershipApprovalController extends Controller
{
    public function index(): View
    {
        $pendingUsers = User::query()
            ->where('role', 'pengguna')
            ->where('membership_status', 'pending')
            ->latest()
            ->get();


        return view('admin.memberships.requests', [
            'pendingUsers' => $pendingUsers,
        ]);
    }

    public function approve(Request $request, User $user): RedirectResponse
    {
        if ($user->hasPremiumAccess()) {
            return redirect()->route('admin.membership-requests.index')
                ->with('success', 'Akses premium user sudah aktif.');
        }

        DB::transaction(function () use ($user): void {
            // Rule #3 (Admin approve)
            // - membership.status = active (users.membership_status)
            // - membership_status = active
            // - users.role = premium
            $lockedUser = User::query()->whereKey($user->id)->lockForUpdate()->firstOrFail();
            $lockedUser->membership_status = 'active';
            $lockedUser->role = 'premium';

            // start_date/end_date may not exist in current schema.
            // Only set them if columns are present to avoid SQL errors.
            if (in_array('start_date', $lockedUser->getFillable(), true) || $lockedUser->offsetExists('start_date')) {
                $lockedUser->start_date = now()->toDateString();
            }
            if (in_array('end_date', $lockedUser->getFillable(), true) || $lockedUser->offsetExists('end_date')) {
                $lockedUser->end_date = now()->addMonth()->toDateString();
            }

            $lockedUser->save();

            // Backward-compatible sync: jika ada request upgrade pending, selaraskan statusnya.
            $pendingUpgrade = MembershipUpgrade::query()
                ->where('user_id', $lockedUser->id)
                ->where('status', 'pending')
                ->orderByDesc('requested_at')
                ->lockForUpdate()
                ->first();

            if ($pendingUpgrade) {
                $startDate = Carbon::today();
                $pendingUpgrade->status = 'approved';
                $pendingUpgrade->payment_status = 'paid';
                $pendingUpgrade->approved_at = now();
                $pendingUpgrade->rejected_at = null;
                $pendingUpgrade->start_date = $startDate;
                $pendingUpgrade->end_date = Carbon::today()->addMonthsNoOverflow((int) ($pendingUpgrade->months ?: 1));
                $pendingUpgrade->save();
            }
        });

        return redirect()->route('admin.membership-requests.index')
            ->with('success', 'Registrasi berhasil di-approve.');
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        DB::transaction(function () use ($user): void {
            // Rule #4 (Admin reject)
            $lockedUser = User::query()->whereKey($user->id)->lockForUpdate()->firstOrFail();
            $lockedUser->membership_status = 'rejected';
            $lockedUser->role = 'pengguna';
            $lockedUser->save();

            $pendingUpgrade = MembershipUpgrade::query()
                ->where('user_id', $lockedUser->id)
                ->where('status', 'pending')
                ->orderByDesc('requested_at')
                ->lockForUpdate()
                ->first();

            if ($pendingUpgrade) {
                $pendingUpgrade->status = 'rejected';
                $pendingUpgrade->rejected_at = now();
                $pendingUpgrade->approved_at = null;
                $pendingUpgrade->start_date = null;
                $pendingUpgrade->end_date = null;
                $pendingUpgrade->save();
            }
        });

        return redirect()->route('admin.membership-requests.index')
            ->with('success', 'Registrasi berhasil di-reject.');
    }
}

