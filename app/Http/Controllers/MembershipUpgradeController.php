<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MembershipUpgrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MembershipUpgradeController extends Controller
{
    private function guardCanRequestUpgrade(Request $request): void
    {
        $user = $request->user();

        // Guest => tidak boleh
        if (!$user) {
            abort(403);
        }

        // role premium => tidak boleh membuat request baru
        if (($user->role ?? null) === 'premium') {
            abort(403);
        }

        // membership_status selain active => tidak boleh
        // (pending/rejected tidak boleh; active boleh)
        if (($user->membership_status ?? null) !== 'active') {
            abort(403);
        }

        // Jika masih ada request membership_upgrades berstatus pending => tidak boleh
        $hasPendingRequest = MembershipUpgrade::query()
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPendingRequest) {
            abort(403);
        }
    }

    private function planPricing(int $months): ?array
    {
        $prices = [
            3 => 49000,
            6 => 89000,
            12 => 149000,
        ];

        if (!isset($prices[$months])) return null;

        return [
            'months' => $months,
            'amount' => $prices[$months],
        ];
    }

    public function plans(Request $request)
    {
        $this->guardCanRequestUpgrade($request);

        return view('membership.upgrade.plans');
    }

    public function review(Request $request)
    {
        $this->guardCanRequestUpgrade($request);

        $data = $request->validate([
            'months' => ['required', 'integer', Rule::in([3, 6, 12])],
        ]);

        // Create request record immediately (review step)
        $pricing = $this->planPricing((int)$data['months']);
        abort_if(!$pricing, 422);

        $upgrade = MembershipUpgrade::create([
            'user_id' => Auth::id(),
            'months' => $pricing['months'],
            'amount' => $pricing['amount'],
            'payment_status' => 'unpaid',
            'payment_method' => null,
            'status' => 'pending',
            'requested_at' => now(),
            'approved_at' => null,
            'rejected_at' => null,
        ]);

        return redirect()->route('membership.upgrade.payment', $upgrade);
    }

    public function payment(Request $request, MembershipUpgrade $upgrade)
    {
        abort_if($upgrade->user_id !== Auth::id(), 403);

        // Prevent double-flow while paid request exists
        if ($upgrade->payment_status === 'paid') {
            return redirect()->route('membership.upgrade.finish', $upgrade);
        }

        return view('membership.upgrade.payment', [
            'upgrade' => $upgrade,
        ]);
    }

    public function pay(Request $request, MembershipUpgrade $upgrade)
    {
        abort_if($upgrade->user_id !== Auth::id(), 403);

        // requirement: after dummy payment successful,
        // save payment_status=paid and status=pending.
        // NOTE: jangan pernah mengubah users.membership_status ketika upgrade premium.
        $data = $request->validate([
            'payment_method' => ['required', 'string', 'max:50'],
        ]);

        $upgrade->payment_method = $data['payment_method'];
        $upgrade->payment_status = 'paid';
        $upgrade->status = 'pending';
        $upgrade->save();

        return redirect()->route('membership.upgrade.finish', $upgrade);
    }

    public function finish(Request $request, MembershipUpgrade $upgrade)
    {
        abort_if($upgrade->user_id !== Auth::id(), 403);

        return view('membership.upgrade.finish', [
            'upgrade' => $upgrade,
        ]);
    }
}

