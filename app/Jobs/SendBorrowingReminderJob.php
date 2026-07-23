<?php

namespace App\Jobs;

use App\Models\Borrowing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendBorrowingReminderJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        $today = now()->startOfDay();
        $targetDate = $today->copy()->addDays(3);

        $dueSoonCount = Borrowing::query()
            ->where('status', 'paid')
            ->whereDate('due_date', '=', $targetDate->toDateString())
            ->count();

        $overdueCount = Borrowing::query()
            ->where('status', 'paid')
            ->whereDate('due_date', '<', $today->toDateString())
            ->count();

        // Lightweight placeholder automation until notification channels are wired.
        Log::info('Borrowing reminders aggregated', [
            'due_in_3_days' => $dueSoonCount,
            'overdue' => $overdueCount,
        ]);
    }
}
