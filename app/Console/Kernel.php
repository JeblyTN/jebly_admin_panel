<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('app:send-scheduled-order-notification')->everyMinute();
        $schedule->exec(
            config('firebase.node_path', '/opt/alt/alt-nodejs20/root/usr/bin/node') . ' ' .
            base_path('scripts/generate_payouts.js') . ' ' .
            base_path('storage/app/firebase/credentials.json')
        )
            ->weeklyOn(1, '00:01')
            ->timezone('Africa/Tunis')
            ->appendOutputTo(storage_path('logs/payouts.log'));
        /*$schedule->command('app:auto-cancel-order')->everyMinute();*/
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
