<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        // Rule #3 (Admin approve)
        // - membership.status = active (users.membership_status)
        // - membership_status = active
        // - users.role = premium
        $user->membership_status = 'active';
        $user->role = 'premium';

        // start_date/end_date may not exist in current schema.
        // Only set them if columns are present to avoid SQL errors.
        if (in_array('start_date', $user->getFillable(), true) || $user->offsetExists('start_date')) {
            $user->start_date = now()->toDateString();
        }
        if (in_array('end_date', $user->getFillable(), true) || $user->offsetExists('end_date')) {
            // If months/duration is not available in this controller, fall back safely.
            // Keep behavior minimal to avoid impacting other features.
            $user->end_date = now()->addMonth()->toDateString();
        }

        $user->save();

        return redirect()->route('admin.membership-requests.index')
            ->with('success', 'Registrasi berhasil di-approve.');
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        // Rule #4 (Admin reject)
        $user->membership_status = 'rejected';
        $user->role = 'pengguna';
        $user->save();

        return redirect()->route('admin.membership-requests.index')
            ->with('success', 'Registrasi berhasil di-reject.');
    }
}

