<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingCount = \App\Models\MembershipUpgrade::query()
            ->where('status', 'pending')
            ->count();

        return view('admin.dashboard', [
            'membershipPendingCount' => $pendingCount,
        ]);
    }

}
