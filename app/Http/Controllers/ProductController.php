<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function fetchProductsWithMetafields()
    {
        $storeDomain = env('SHOPIFY_STORE_DOMAIN');
        $accessToken = env('SHOPIFY_ACCESS_TOKEN');

        if (!$storeDomain || !$accessToken) {
            return response()->json(['error' => 'Missing Shopify credentials'], 400);
        }

        $url = "https://{$storeDomain}/admin/api/2024-07/graphql.json";

        $query = <<<GRAPHQL
        {
          products(first: 100) {
            edges {
              node {
                id
                title
                variants(first: 1) {
                  edges {
                    node {
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
        ])->post($url, [
            'query' => $query
        ]);

        if (!$response->successful()) {
            return response()->json(['error' => 'Failed to fetch products from Shopify GraphQL'], 500);
        }

        $products = $response->json('data.products.edges');
        $shopifyProductIds = [];

        foreach ($products as $productEdge) {
            $node = $productEdge['node'];
            $shopifyId = $this->extractIdFromGid($node['id']);
            $shopifyProductIds[] = $shopifyId;

            $variant = $node['variants']['edges'][0]['node'] ?? null;

            $metafields = [];

            foreach ($node['metafields']['edges'] as $metaEdge) {
                $meta = $metaEdge['node'];

                $metafield = [
                    'namespace' => $meta['namespace'],
                    'key'       => $meta['key'],
                    'type'      => $meta['type'],
                    'value'     => $meta['value'],
                    'updatedAt' => $meta['updatedAt'],
                ];

                if ($meta['type'] === 'metaobject_reference' && isset($meta['reference'])) {
                    $fields = $meta['reference']['fields'];
                    $resolved = [];
                    foreach ($fields as $field) {
                        $resolved[$field['key']] = $field['value'];
                    }
                    $metafield['value'] = $resolved;
                }

                $metafields[] = $metafield;
            }

            Product::updateOrCreate(
                ['shopify_product_id' => $shopifyId],
                [
                    'title' => $node['title'],
                    'price' => $variant['price'] ?? null,
                    'sku' => $variant['sku'] ?? null,
                    'inventory_quantity' => $variant['inventoryQuantity'] ?? 0,
                    'image_url' => $node['images']['edges'][0]['node']['transformedSrc'] ?? null,
                    'metafields' => $metafields,
                ]
            );
        }

        // Remove deleted products
        Product::whereNotIn('shopify_product_id', $shopifyProductIds)->delete();

        return response()->json([
            'message' => 'Products synced successfully.',
            'fetched' => count($products),
        ]);
    }

    public function showProducts()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    private function extractIdFromGid($gid)
    {
        return basename($gid);
    }
}
