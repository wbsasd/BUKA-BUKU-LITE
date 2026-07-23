<?php

namespace App\Console\Commands;

use App\Jobs\SyncMembershipExpiryJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class SyncMembershipExpiryCommand extends Command
{
    protected $signature = 'membership:sync-expiry';

    protected $description = 'Sync expired membership upgrades and legacy user membership flags';

    public function handle(): int
    {
        Bus::dispatchSync(new SyncMembershipExpiryJob());
        $this->info('Membership expiry sync executed.');

        return self::SUCCESS;
    }
}
