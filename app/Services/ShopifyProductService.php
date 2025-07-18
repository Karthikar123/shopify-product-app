<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; 
use App\Models\Product;

class ShopifyProductService
{
    public function fetchAndStoreProducts()
    {
        $shop = env('SHOPIFY_STORE_DOMAIN');
        $token = env('SHOPIFY_ACCESS_TOKEN');

        if (!$shop || !$token) {
            Log::error('Shopify credentials are missing in .env');
            throw new \Exception('Missing Shopify credentials');
        }

        $url = "https://{$shop}/admin/api/2024-07/products.json";

        Log::info("Fetching Shopify products from: " . $url);

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $token,
            'Content-Type' => 'application/json',
        ])->get($url);

        if (!$response->successful()) {
            Log::error('Shopify API error: ' . $response->body());
            throw new \Exception('Failed to fetch products from Shopify: ' . $response->body());
        }

        $products = $response->json('products') ?? [];

        foreach ($products as $product) {
            $price = $product['variants'][0]['price'] ?? 0.00;
            $metafields = $this->fetchMetafields($product['id'], $shop, $token);

            Product::updateOrCreate(
    ['shopify_product_id' => $product['id']],
    [
        'title' => $product['title'],
        'price' => $price,
        'metafields' => json_encode($metafields),
        'image_url' => $product['image']['src'] ?? null,
        'inventory_quantity' => $product['variants'][0]['inventory_quantity'] ?? null,
        'sku' => $product['variants'][0]['sku'] ?? null,
        'body_html' => $product['body_html'] ?? null,
    ]
);

        }

        Log::info('Products imported successfully');
    }

    private function fetchMetafields($productId, $shop, $token)
    {
        $url = "https://{$shop}/admin/api/2024-07/products/{$productId}/metafields.json";

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $token,
            'Content-Type' => 'application/json',
        ])->get($url);

        if (!$response->successful()) {
            Log::warning("Failed to fetch metafields for Product ID: {$productId}");
            return [];
        }

        return $response->json('metafields') ?? [];
    }
}
