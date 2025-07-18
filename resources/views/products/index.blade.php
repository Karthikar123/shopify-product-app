<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopify Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Segoe UI', sans-serif;
        }
        h2 {
            color: #333;
            font-weight: 600;
            font-size: 1.8rem;
        }
        .card {
            border-radius: 10px;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
            padding: 8px;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
        }
        .card-img-top {
            max-height: 200px;
            object-fit: cover;
        }
        .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: #222;
        }
        .card-text {
            font-size: 0.85rem;
            color: #555;
        }
        .card p {
            margin-bottom: 0.4rem;
            font-size: 0.85rem;
        }
        .metafield-label {
            font-weight: 600;
            color: #444;
            font-size: 0.85rem;
        }
        .metafield-group {
            background-color: #f0f0f0;
            border-radius: 6px;
            padding: 6px 10px;
            margin-bottom: 8px;
        }
        .color-swatch {
            display: inline-block;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            margin-right: 6px;
            border: 1px solid #aaa;
            vertical-align: middle;
        }
        ul {
            padding-left: 16px;
            margin-bottom: 0;
        }
        li {
            font-size: 0.8rem;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center mb-4">Shopify Products</h2>
    <div class="row">
        @foreach ($products as $product)
            <div class="col-12 col-sm-6 col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="{{ $product->image_url ?? 'https://via.placeholder.com/300x300?text=No+Image' }}" class="card-img-top" alt="{{ $product->title }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->title }}</h5>
                        <p class="card-text">{!! $product->body_html ?? 'No description' !!}</p>
                        <p><strong>Price:</strong> ₹{{ $product->price ?? 'N/A' }}</p>
                        <p><strong>SKU:</strong> {{ $product->sku ?? 'N/A' }}</p>
                        <p><strong>Inventory:</strong> {{ $product->inventory_quantity ?? 'N/A' }}</p>

                        {{-- Metafields --}}
                        @if (!empty($product->decoded_metafields))
                            <div class="mt-2">
                                <strong>Metafields:</strong>
                                @foreach ($product->decoded_metafields as $metafield)
                                    @php
                                        $skipNamespaces = ['mm-google-shopping'];
                                        if (in_array($metafield['namespace'], $skipNamespaces)) continue;

                                        $namespaceClean = str_replace(['_', '-', ':'], ' ', $metafield['namespace']);
                                        $keyClean = str_replace(['_', '-', ':'], ' ', $metafield['key']);
                                        $keyName = ucwords(trim($namespaceClean . ' ' . $keyClean));

                                        $value = $metafield['resolved_value'] ?? $metafield['value'] ?? '';
                                        $decoded = json_decode($value, true);
                                    @endphp

                                    <div class="metafield-group">
                                        <div class="metafield-label">{{ $keyName }}:</div>

                                        @if (is_array($decoded))
                                            <ul>
                                                @foreach ($decoded as $subKey => $subVal)
                                                    <li>
                                                        <strong>{{ ucwords(str_replace('_', ' ', $subKey)) }}:</strong>
                                                        @if ($subKey === 'color' && preg_match('/^#([A-Fa-f0-9]{6})$/', $subVal))
                                                            <span class="color-swatch" style="background-color: {{ $subVal }}"></span> {{ $subVal }}
                                                        @elseif (is_array($subVal))
                                                            {{ implode(', ', array_map(function($v) {
                                                                return is_string($v) ? preg_replace('/gid:\/\/shopify\/[^\/]+\/(\d+)/', '#$1', $v) : $v;
                                                            }, $subVal)) }}
                                                        @else
                                                            {{ is_string($subVal) ? preg_replace('/gid:\/\/shopify\/[^\/]+\/(\d+)/', '#$1', $subVal) : $subVal }}
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div>
                                                {{ is_string($value) ? preg_replace('/gid:\/\/shopify\/[^\/]+\/(\d+)/', '#$1', $value) : $value }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
</body>
</html>
