<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Send appointment reminders every hour
        $schedule->command('appointments:send-reminders')
                 ->hourly()
                 ->withoutOverlapping();

        // Check waiting times every 15 minutes during working hours (8 AM - 6 PM)
        $schedule->command('appointments:check-waiting-times')
                 ->everyFifteenMinutes()
                 ->between('8:00', '18:00')
                 ->withoutOverlapping();

        // Clean up old notifications monthly
        $schedule->command('model:prune')
                 ->monthly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}