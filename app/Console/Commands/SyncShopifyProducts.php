<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ShopifyProductService;

class SyncShopifyProducts extends Command
{
    protected $signature = 'shopify:sync-products';
    protected $description = 'Sync products from Shopify and store in database';

    public function handle()
    {
        try {
            app(ShopifyProductService::class)->fetchAndStoreProducts();
            $this->info('✅ Products synced successfully.');
        } catch (\Exception $e) {
            $this->error('❌ Error syncing products: ' . $e->getMessage());
        }
    }
}
