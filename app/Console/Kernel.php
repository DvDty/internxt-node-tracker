<?php

namespace App\Console;

use App\Console\Commands\AddressClear;
use App\Console\Commands\NodeReputations;
use App\Console\Commands\NodeStatuses;
use App\Console\Commands\NodesUpdate;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(NodesUpdate::class)->withoutOverlapping()->everyFifteenMinutes();
        $schedule->command(NodeStatuses::class)->withoutOverlapping()->everyTenMinutes();
        $schedule->command(NodeReputations::class)->withoutOverlapping()->daily();
        $schedule->command(AddressClear::class)->withoutOverlapping()->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
