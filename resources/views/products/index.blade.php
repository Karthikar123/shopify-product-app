@extends('layouts.app')

@section('content')
    <div class="page-title">Inventory - Shopify Products</div>

    {{-- Success and Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    {{-- Sync Button --}}
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('products.sync.now') }}" class="btn btn-success">
            🔄 Sync Now from Shopify
        </a>
    </div>

    {{-- Product Table --}}
    <div class="card p-3">
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
                                    <img src="{{ $product->image_url }}" alt="Product Image" class="img-thumbnail" style="max-height: 80px; max-width: 80px;">
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
@endsection
