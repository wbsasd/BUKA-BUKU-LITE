<?php

namespace App\Jobs;

use App\Models\MembershipUpgrade;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncMembershipExpiryJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        DB::transaction(function (): void {
            $today = now()->toDateString();

            $expiredRows = MembershipUpgrade::query()
                ->whereIn('status', ['approved', 'active'])
                ->whereNotNull('end_date')
                ->whereDate('end_date', '<', $today)
                ->update([
                    'status' => 'expired',
                ]);

            // Backward compatibility sync for legacy fields on users table.
            $premiumUsers = User::query()
                ->where('role', 'premium')
                ->where('membership_status', 'active')
                ->get();

            $downgraded = 0;
            foreach ($premiumUsers as $user) {
                $hasActive = MembershipUpgrade::query()
                    ->where('user_id', $user->id)
                    ->whereIn('status', ['approved', 'active'])
                    ->where(function ($q): void {
                        $q->whereNull('end_date')->orWhereDate('end_date', '>=', now()->toDateString());
                    })
                    ->exists();

                if ($hasActive) {
                    continue;
                }

                $user->role = 'pengguna';
                $user->membership_status = 'expired';
                $user->save();
                $downgraded++;
            }

            Log::info('Membership expiry sync completed', [
                'expired_upgrades' => $expiredRows,
                'downgraded_users' => $downgraded,
            ]);
        });
    }
}
