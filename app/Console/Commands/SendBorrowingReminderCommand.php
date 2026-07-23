<?php

namespace App\Console\Commands;

use App\Jobs\SendBorrowingReminderJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class SendBorrowingReminderCommand extends Command
{
    protected $signature = 'borrowing:send-reminders';

    protected $description = 'Dispatch borrowing reminder aggregation job';

    public function handle(): int
    {
        Bus::dispatchSync(new SendBorrowingReminderJob());
        $this->info('Borrowing reminder aggregation executed.');

        return self::SUCCESS;
    }
}
