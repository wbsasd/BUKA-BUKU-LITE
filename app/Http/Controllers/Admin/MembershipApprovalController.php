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
        $user->membership_status = 'active';
        $user->save();

        return redirect()->route('admin.membership-requests.index')
            ->with('success', 'Registrasi berhasil di-approve.');
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        $user->membership_status = 'rejected';
        $user->save();

        return redirect()->route('admin.membership-requests.index')
            ->with('success', 'Registrasi berhasil di-reject.');
    }
}

