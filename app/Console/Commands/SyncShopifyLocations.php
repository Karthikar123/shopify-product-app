<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Location;

class SyncShopifyLocations extends Command
{
    /**
     * The name and signature of the console command.
     * Used to run the command via Artisan CLI.
     */
    protected $signature = 'shopify:sync-locations';

    /**
     * The console command description.
     * Used for listing in Artisan commands list.
     */
    protected $description = 'Fetch and store Shopify warehouse locations into database';

    /**
     * Execute the console command.
     * Makes API call to Shopify, retrieves locations (warehouses),
     * and stores/updates them in the local database.
     */
    public function handle()
    {
        // Validate required .env configuration
        if (!env('SHOPIFY_ACCESS_TOKEN') || !env('SHOPIFY_STORE_DOMAIN')) {
            $this->error('Missing Shopify credentials in .env file.');
            return Command::FAILURE;
        }

        // Prepare Shopify API URL
        $url = 'https://' . env('SHOPIFY_STORE_DOMAIN') . '/admin/api/2024-04/locations.json';

        // Send GET request to Shopify API to fetch locations
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => env('SHOPIFY_ACCESS_TOKEN'),
        ])->get($url);

        // If request is successful, process and store locations
        if ($response->successful()) {
            $locations = $response->json('locations');

            foreach ($locations as $loc) {
                // Insert or update location in local database
                Location::updateOrCreate(
                    ['location_id' => $loc['id']],
                    [
                        'name' => $loc['name'],
                        'address' => $loc['address1'] ?? '',
                    ]
                );
            }

            $this->info('Locations synced successfully.');
        } else {
            // If request fails, show error message with response details
            $this->error('Failed to fetch locations from Shopify.');
            $this->error('Status Code: ' . $response->status());
            $this->error('Response Body: ' . $response->body());
        }

        return Command::SUCCESS;
    }
}
