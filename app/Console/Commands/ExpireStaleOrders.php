<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class ExpireStaleOrders extends Command
{
    protected $signature = 'orders:expire-stale';

    protected $description = 'Mark pending orders past their expires_at as expired';

    public function handle(): int
    {
        $count = Order::where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);

        if ($count > 0) {
            $this->info("Marked {$count} stale order(s) as expired.");
        }

        return self::SUCCESS;
    }
}
