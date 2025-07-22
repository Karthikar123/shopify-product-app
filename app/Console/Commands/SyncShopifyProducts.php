<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ShopifyProductService;

class SyncShopifyProducts extends Command
{
    // Defines the command signature that can be used in the terminal
    protected $signature = 'shopify:sync-products';

    // Describes what the command does
    protected $description = 'Sync products from Shopify and store in database';

    /**
     * Execute the console command.
     * This function fetches and stores products from Shopify via the service class.
     */
    public function handle()
    {
        try {
            // Call the ShopifyProductService to fetch and save products
            app(ShopifyProductService::class)->fetchAndStoreProducts();
            
            // Show success message in terminal
            $this->info('✅ Products synced successfully.');
        } catch (\Exception $e) {
            // Show error message in case of failure
            $this->error('❌ Error syncing products: ' . $e->getMessage());
        }
    }
}
