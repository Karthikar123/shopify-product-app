<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Product;

class ProductController extends Controller
{
    public function showProducts()
    {
        $products = Product::all();

        $shop = config('services.shopify.store_domain');
        $accessToken = config('services.shopify.access_token');
        $version = '2023-10';

        foreach ($products as $product) {
            $decodedMetafields = [];

            if (!empty($product->metafields)) {
                $metafields = json_decode($product->metafields, true);

                if (is_array($metafields)) {
                    foreach ($metafields as $metafield) {
                        $value = $metafield['value'] ?? null;

                        if (is_string($value) && str_starts_with($value, 'gid://shopify/Metaobject/')) {
                            $resolved = $this->resolveMetaobject($shop, $accessToken, $version, $value);
                            $metafield['resolved_value'] = $resolved ?? $value;
                        } elseif (is_string($value) && str_starts_with($value, '[')) {
                            // Handle arrays of GIDs
                            $decodedArray = json_decode($value, true);
                            if (is_array($decodedArray)) {
                                $resolvedList = collect($decodedArray)->map(function ($gid) use ($shop, $accessToken, $version) {
                                    if (is_string($gid) && str_starts_with($gid, 'gid://shopify/Metaobject/')) {
                                        return $this->resolveMetaobject($shop, $accessToken, $version, $gid);
                                    }
                                    return $gid;
                                })->filter()->values()->all();
                                $metafield['resolved_value'] = implode(', ', $resolvedList);
                            } else {
                                $metafield['resolved_value'] = $value;
                            }
                        } else {
                            $metafield['resolved_value'] = $value;
                        }

                        $decodedMetafields[] = $metafield;
                    }
                }
            }

            $product->decoded_metafields = $decodedMetafields;
        }

        return view('products.index', compact('products'));
    }

    public function fetchProductsWithMetafields()
    {
        $shop = config('services.shopify.store_domain');
        $accessToken = config('services.shopify.access_token');
        $version = '2023-10';

        $headers = [
            'X-Shopify-Access-Token' => $accessToken,
        ];

        $productsResponse = Http::withHeaders($headers)->get("https://{$shop}/admin/api/{$version}/products.json");

        if (!$productsResponse->successful()) {
            return response()->json(['error' => 'Failed to fetch products'], 500);
        }

        $products = $productsResponse->json('products');

        foreach ($products as $product) {
            $metafieldsResponse = Http::withHeaders($headers)
                ->get("https://{$shop}/admin/api/{$version}/products/{$product['id']}/metafields.json");

            $metafields = $metafieldsResponse->json('metafields') ?? [];

            Product::updateOrCreate(
                ['shopify_product_id' => $product['id']],
                [
                    'title' => $product['title'],
                    'price' => $product['variants'][0]['price'] ?? null,
                    'metafields' => json_encode($metafields),
                    'image_url' => $product['image']['src'] ?? null,
                    'inventory_quantity' => $product['variants'][0]['inventory_quantity'] ?? null,
                    'sku' => $product['variants'][0]['sku'] ?? null,
                    'body_html' => $product['body_html'] ?? null,
                ]
            );
        }

        return response()->json(['message' => 'Products and metafields saved successfully.']);
    }

    private function resolveMetaobject($shop, $accessToken, $version, $gid)
    {
        $query = <<<GQL
        query {
            node(id: "{$gid}") {
                ... on Metaobject {
                    type
                    fields {
                        key
                        value
                    }
                }
            }
        }
        GQL;

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken,
            'Content-Type' => 'application/json',
        ])->post("https://{$shop}/admin/api/{$version}/graphql.json", [
            'query' => $query
        ]);

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();

        if (!empty($data['data']['node']['fields'])) {
            // Try to extract the title or most relevant field
            foreach ($data['data']['node']['fields'] as $field) {
                if ($field['key'] === 'title' || $field['key'] === 'name') {
                    return $field['value'];
                }
            }

            // Fallback: return key:value string
            return collect($data['data']['node']['fields'])
                ->map(fn($f) => "{$f['key']}: {$f['value']}")
                ->implode(', ');
        }

        return null;
    }
}
