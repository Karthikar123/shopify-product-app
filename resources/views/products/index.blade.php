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
            font-size: 14px;
        }

        th, td {
            vertical-align: middle !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        th {
            font-weight: 600;
        }

        td img {
            max-height: 80px;
            max-width: 80px;
            object-fit: contain;
        }

        .w-img    { width: 90px; }
        .w-title  { width: 180px; }
        .w-price,
        .w-sku,
        .w-stock  { width: 80px; }
        .w-meta {
            min-width: 80px;
            max-width: 120px;
        }
        .w-actions { width: 90px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Shopify Products</h1>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('products.sync.now') }}" class="btn btn-success">
            🔄 Sync Now from Shopify
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th class="w-img">Image</th>
                    <th class="w-title text-start">Product Title</th>
                    <th class="w-price">Price (₹)</th>
                    <th class="w-sku">SKU</th>
                    <th class="w-stock">Stock</th>

                    @php
                        $pinnedKeys = ['origin', 'feature', 'care_guide', 'fabric_care'];
                    @endphp

                    @foreach ($pinnedKeys as $key)
                        <th class="w-meta">{{ ucwords(str_replace('_', ' ', $key)) }}</th>
                    @endforeach

                    <th class="w-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    @php
                        $metafieldMap = is_array($product->metafields)
                            ? $product->metafields
                            : (json_decode($product->metafields, true) ?? []);
                    @endphp

                    <tr>
                        <td>
                            @if ($product->image_url)
                                <img src="{{ $product->image_url }}" alt="Product Image" class="img-thumbnail">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td class="text-start">{{ $product->title }}</td>
                        <td>₹{{ number_format($product->price ?? 0, 2) }}</td>
                        <td>{{ $product->sku ?? 'N/A' }}</td>
                        <td>{{ $product->inventory_quantity ?? 'N/A' }}</td>

                        @foreach ($pinnedKeys as $key)
                            <td class="text-start w-meta">
                                {{ $metafieldMap[$key] ?? 'N/A' }}
                            </td>
                        @endforeach

                        <td>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%" class="text-center">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
