<?php

namespace App\Console\Commands;

use App\Jobs\SendMembershipReminderJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class SendMembershipReminderCommand extends Command
{
    protected $signature = 'membership:send-reminders';

    protected $description = 'Dispatch membership reminder aggregation job';

    public function handle(): int
    {
        Bus::dispatchSync(new SendMembershipReminderJob());
        $this->info('Membership reminder aggregation executed.');

        return self::SUCCESS;
    }
}
