<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register custom Artisan commands for the application.
     * This allows Laravel to recognize and execute custom commands,
     * such as SyncShopifyProducts.
     */
    protected $commands = [
        \App\Console\Commands\SyncShopifyProducts::class,
    ];

    /**
     * Define the application's scheduled tasks.
     * This schedules the `shopify:sync-products` command
     * to run every five minutes.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('shopify:sync-products')->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     * Loads all Artisan commands from the Commands directory.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
