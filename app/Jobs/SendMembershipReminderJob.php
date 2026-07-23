<?php

namespace App\Jobs;

use App\Models\MembershipUpgrade;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendMembershipReminderJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        $targetDate = now()->addDays(7)->toDateString();

        $expiringSoonCount = MembershipUpgrade::query()
            ->whereIn('status', ['approved', 'active'])
            ->whereDate('end_date', '=', $targetDate)
            ->count();

        Log::info('Membership reminders aggregated', [
            'expiring_in_7_days' => $expiringSoonCount,
        ]);
    }
}
