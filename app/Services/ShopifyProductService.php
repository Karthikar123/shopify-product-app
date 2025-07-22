<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;

class ShopifyProductService
{
    /**
     * Fetches all products from the Shopify API and stores them in the local database.
     * - Handles pagination automatically.
     * - Skips products marked as manually edited.
     * - Fetches and stores product metafields.
     */
    public function fetchAndStoreProducts()
    {
        $shop = env('SHOPIFY_STORE_DOMAIN');
        $token = env('SHOPIFY_ACCESS_TOKEN');

        if (!$shop || !$token) {
            Log::error('❌ Shopify credentials are missing in .env');
            throw new \Exception('Missing Shopify credentials');
        }

        $limit = 250;
        $baseUrl = "https://{$shop}/admin/api/2024-07/products.json?limit={$limit}";
        $url = $baseUrl;

        do {
            Log::info("📦 Fetching products from: {$url}");

            $response = Http::withHeaders([
                'X-Shopify-Access-Token' => $token,
                'Content-Type' => 'application/json',
            ])->get($url);

            if (!$response->successful()) {
                Log::error('❌ Shopify API error: ' . $response->body());
                throw new \Exception('Failed to fetch products from Shopify: ' . $response->body());
            }

            $products = $response->json('products') ?? [];

            Log::info("🧮 Fetched " . count($products) . " products.");

            foreach ($products as $product) {
                try {
                    $existingProduct = Product::where('shopify_product_id', $product['id'])->first();

                    // ✅ Skip manually edited products
                    if ($existingProduct && $existingProduct->is_edited) {
                        Log::info("⏭️ Skipping manually edited product ID {$product['id']}");
                        continue;
                    }

                    $price = $product['variants'][0]['price'] ?? 0.00;

                    // 🧩 Fetch metafields for the current product
                    $metafields = $this->fetchMetafields($product['id'], $shop, $token);

                    // 📝 Save or update product in local DB
                    Product::updateOrCreate(
                        ['shopify_product_id' => $product['id']],
                        [
                            'title' => $product['title'],
                            'price' => $price,
                            'metafields' => $metafields,
                            'image_url' => $product['image']['src'] ?? null,
                            'inventory_quantity' => $product['variants'][0]['inventory_quantity'] ?? null,
                            'sku' => $product['variants'][0]['sku'] ?? null,
                            'body_html' => $product['body_html'] ?? null,
                            'is_edited' => $existingProduct ? $existingProduct->is_edited : false,
                        ]
                    );

                } catch (\Exception $e) {
                    Log::error("❌ Failed to sync product ID {$product['id']}: " . $e->getMessage());
                }
            }

            // 🔁 Get next page URL from pagination header (if any)
            $linkHeader = $response->header('Link');
            $url = $this->getNextPageUrl($linkHeader);

        } while ($url); // continue if there is a next page

        Log::info('✅ All Shopify products imported successfully.');
    }

    /**
     * Parses the 'Link' header to retrieve the next page URL for pagination.
     *
     * @param string|null $linkHeader
     * @return string|null
     */
    private function getNextPageUrl($linkHeader)
    {
        if (!$linkHeader) {
            return null;
        }

        preg_match('/<([^>]+)>; rel="next"/', $linkHeader, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Fetches all metafields for a given Shopify product.
     *
     * @param int $productId
     * @param string $shop
     * @param string $token
     * @return array
     */
    private function fetchMetafields($productId, $shop, $token)
    {
        $url = "https://{$shop}/admin/api/2024-07/products/{$productId}/metafields.json";

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $token,
            'Content-Type' => 'application/json',
        ])->get($url);

        if (!$response->successful()) {
            Log::warning("⚠️ Failed to fetch metafields for Product ID: {$productId}");
            return [];
        }

        $rawMetafields = $response->json('metafields') ?? [];

        // 🧰 Flatten metafields using namespace.key => value format
        $flattened = [];
        foreach ($rawMetafields as $field) {
            $key = $field['namespace'] . '.' . $field['key'];
            $flattened[$key] = $field['value'];
        }

        return $flattened;
    }
}
