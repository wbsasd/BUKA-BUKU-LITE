<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class DashboardBorrowingCountController extends Controller
{
    public function __invoke(Request $request)
    {
        $count = Borrowing::query()
            ->where('user_id', auth()->id())
            ->where('status', '!=', 'returned')
            ->count();

        return response()->json([
            'count' => $count,
        ]);
    }
}

