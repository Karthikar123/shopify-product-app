<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Fetches products from Shopify GraphQL API and syncs them with the local database.
     * - Skips locally edited products.
     * - Updates or creates product records.
     * - Deletes products not present in the latest Shopify response.
     */
    public function syncFromShopify()
    {
        $storeDomain = env('SHOPIFY_STORE_DOMAIN');
        $accessToken = env('SHOPIFY_ACCESS_TOKEN');

        if (!$storeDomain || !$accessToken) {
            return response()->json(['error' => 'Missing Shopify credentials'], 400);
        }

        $url = "https://{$storeDomain}/admin/api/2024-07/graphql.json";

        // GraphQL query to fetch products, variants, metafields, and images
        $query = <<<GRAPHQL
        {
          products(first: 100) {
            edges {
              node {
                id
                title
                descriptionHtml
                variants(first: 1) {
                  edges {
                    node {
                      id
                      price
                      sku
                      inventoryQuantity
                    }
                  }
                }
                metafields(first: 20) {
                  edges {
                    node {
                      namespace
                      key
                      type
                      value
                      updatedAt
                      reference {
                        ... on Metaobject {
                          id
                          fields {
                            key
                            value
                          }
                        }
                      }
                    }
                  }
                }
                images(first: 1) {
                  edges {
                    node {
                      transformedSrc
                    }
                  }
                }
              }
            }
          }
        }
        GRAPHQL;

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken,
            'Content-Type' => 'application/json',
        ])->post($url, ['query' => $query]);

        if (!$response->successful()) {
            return response()->json(['error' => 'Failed to fetch products from Shopify GraphQL'], 500);
        }

        $products = $response->json('data.products.edges');
        $shopifyProductIds = [];

        foreach ($products as $productEdge) {
            $node = $productEdge['node'];
            $shopifyId = $this->extractIdFromGid($node['id']);
            $shopifyProductIds[] = $shopifyId;

            // Skip manually edited products
            $existingProduct = Product::where('shopify_product_id', $shopifyId)->first();
            if ($existingProduct && $existingProduct->is_edited) {
                continue;
            }

            $variant = $node['variants']['edges'][0]['node'] ?? null;
            $shopifyVariantId = isset($variant['id']) ? $this->extractIdFromGid($variant['id']) : null;

            // Parse metafields
            $metafields = [];
            foreach ($node['metafields']['edges'] as $metaEdge) {
                $meta = $metaEdge['node'];
                if ($meta['type'] === 'metaobject_reference' && isset($meta['reference'])) {
                    foreach ($meta['reference']['fields'] as $field) {
                        $metafields[$meta['key']][$field['key']] = $field['value'];
                    }
                } else {
                    $metafields[$meta['key']] = $meta['value'];
                }
            }

            // Save or update product locally
            Product::updateOrCreate(
                ['shopify_product_id' => $shopifyId],
                [
                    'shopify_variant_id' => $shopifyVariantId,
                    'title' => $node['title'],
                    'body_html' => $node['descriptionHtml'] ?? null,
                    'price' => $variant['price'] ?? null,
                    'sku' => $variant['sku'] ?? null,
                    'inventory_quantity' => $variant['inventoryQuantity'] ?? 0,
                    'image_url' => $node['images']['edges'][0]['node']['transformedSrc'] ?? null,
                    'metafields' => $metafields,
                    'is_edited' => $existingProduct ? $existingProduct->is_edited : false,
                ]
            );
        }

        // Delete local products that are no longer in Shopify
        Product::whereNotIn('shopify_product_id', $shopifyProductIds)->delete();

        return redirect()->route('products.list')
            ->with('success', 'Products synced successfully!');
    }

    /**
     * Displays all synced products in a view.
     */
    public function showProducts()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    /**
     * Loads the edit view for a specific product.
     * 
     * @param int $id Product ID
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    /**
     * Updates a product locally and attempts to sync the changes back to Shopify.
     * 
     * @param Request $request
     * @param int $id Product ID
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'price' => 'nullable|numeric',
            'metafields' => 'nullable|array',
        ]);

        $product = Product::findOrFail($id);

        // Update local DB record
        $product->title = $request->input('title');
        $product->price = $request->input('price');
        $product->metafields = $request->input('metafields', []);
        $product->is_edited = true;
        $product->save();

        // Sync update to Shopify
        $storeDomain = env('SHOPIFY_STORE_DOMAIN');
        $accessToken = env('SHOPIFY_ACCESS_TOKEN');

        $shopifyProductId = $product->shopify_product_id;
        $shopifyVariantId = $product->shopify_variant_id;

        if ($storeDomain && $accessToken && $shopifyProductId && $shopifyVariantId) {
            $response = Http::withHeaders([
                'X-Shopify-Access-Token' => $accessToken,
                'Content-Type' => 'application/json',
            ])->put("https://{$storeDomain}/admin/api/2024-07/products/{$shopifyProductId}.json", [
                'product' => [
                    'id' => $shopifyProductId,
                    'title' => $product->title,
                    'variants' => [
                        [
                            'id' => $shopifyVariantId,
                            'price' => $product->price,
                        ]
                    ],
                ]
            ]);

            if (!$response->successful()) {
                return redirect()->back()->with('error', 'Local update saved, but failed to sync with Shopify.');
            }
        }

        return redirect()->route('products.edit', $product->id)
            ->with('success', 'Product updated locally and synced to Shopify!');
    }

    /**
     * Extracts the numeric ID from a Shopify GID (Global ID format).
     * Example: gid://shopify/Product/12345678 → 12345678
     * 
     * @param string $gid
     * @return string
     */
    private function extractIdFromGid($gid)
    {
        return last(explode('/', $gid));
    }
}
