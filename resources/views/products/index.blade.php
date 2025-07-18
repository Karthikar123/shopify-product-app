<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopify Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 40px 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 40px;
            font-weight: bold;
        }

        table {
            background-color: white;
        }

        .metafield-list {
            max-height: 200px;
            overflow-y: auto;
        }

        pre {
            background: #f1f1f1;
            padding: 10px;
            border-radius: 5px;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Shopify Products</h1>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Price (₹)</th>
                    <th>SKU</th>
                    <th>Inventory</th>
                    <th>Metafields</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    @php
                        $metafields = is_array($product->metafields)
                            ? $product->metafields
                            : json_decode($product->metafields, true);
                    @endphp
                    <tr>
                        <td style="width: 100px;">
                            @if ($product->image_url)
                                <img src="{{ $product->image_url }}" alt="Image" class="img-fluid rounded" style="max-height: 100px;">
                            @else
                                <span class="text-muted">No image</span>
                            @endif
                        </td>
                        <td>{{ $product->title }}</td>
                        <td>₹{{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->sku ?? 'N/A' }}</td>
                        <td>{{ $product->inventory_quantity ?? 'N/A' }}</td>
                        <td class="text-start">
                            @if (!empty($metafields))
                                <div class="metafield-list">
                                    <ul class="list-unstyled">
                                        @foreach ($metafields as $key => $meta)
                                            <li>
                                                <strong>{{ ucfirst(str_replace(['_', '-'], ' ', $key)) }}:</strong>
                                                <div class="metafield-value mt-1">
                                                    @php
                                                        if (is_array($meta)) {
                                                            echo '<pre>' . json_encode($meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
                                                        } elseif (is_string($meta) && str_starts_with($meta, '{')) {
                                                            $decoded = json_decode($meta, true);
                                                            if (json_last_error() === JSON_ERROR_NONE) {
                                                                echo '<pre>' . json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
                                                            } else {
                                                                echo e($meta);
                                                            }
                                                        } else {
                                                            echo e($meta);
                                                        }
                                                    @endphp
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <span class="text-muted">No metafields</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
