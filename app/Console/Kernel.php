<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register custom Artisan commands for the application.
     * This allows Laravel to recognize and execute custom commands,
     * such as SyncShopifyProducts and SyncShopifyLocations.
     */
    protected $commands = [
        \App\Console\Commands\SyncShopifyProducts::class,
        \App\Console\Commands\SyncShopifyLocations::class,
    ];

    /**
     * Define the application's scheduled tasks.
     * This schedules:
     * - shopify:sync-products command to run every five minutes.
     * - shopify:sync-locations command to run hourly.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('shopify:sync-products')->everyFiveMinutes();
        $schedule->command('shopify:sync-locations')->hourly();
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
