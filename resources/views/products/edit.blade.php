@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-title">🛠️ Edit Product</div>

    {{-- Success message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Edit Product Form --}}
    <div class="card p-4">
        <form method="POST" action="{{ route('products.update', $product->id) }}">
            @csrf
            @method('PUT')

            {{-- Product Title --}}
            <div class="mb-3">
                <label for="title" class="form-label">Product Title</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $product->title }}">
            </div>

            {{-- Product Price --}}
            <div class="mb-3">
                <label for="price" class="form-label">Product Price (₹)</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $product->price }}">
            </div>

            {{-- Metafields --}}
            <h5 class="mt-4 mb-3">Metafields</h5>

            @php
                $metafields = $product->metafields ?? [];
            @endphp

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="origin" class="form-label">Brand Origin</label>
                    <input type="text" class="form-control" id="origin" name="metafields[origin]" value="{{ $metafields['origin'] ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label for="feature" class="form-label">Feature Tags</label>
                    <input type="text" class="form-control" id="feature" name="metafields[feature]" value="{{ $metafields['feature'] ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label for="care_guide" class="form-label">Care Guide</label>
                    <input type="text" class="form-control" id="care_guide" name="metafields[care_guide]" value="{{ $metafields['care_guide'] ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label for="fabric_care" class="form-label">Fabric Care Instructions</label>
                    <input type="text" class="form-control" id="fabric_care" name="metafields[fabric_care]" value="{{ $metafields['fabric_care'] ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label for="color-pattern" class="form-label">Color Pattern</label>
                    <input type="text" class="form-control" id="color-pattern" name="metafields[color-pattern]" value="{{ $metafields['color-pattern'] ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label for="target-gender" class="form-label">Target Gender</label>
                    <input type="text" class="form-control" id="target-gender" name="metafields[target-gender]" value="{{ $metafields['target-gender'] ?? '' }}">
                </div>
            </div>

            {{-- Submit and Back --}}
            <div class="mt-4 d-flex gap-3">
                <button type="submit" class="btn btn-primary">💾 Update Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">⬅ Back to List</a>
            </div>
        </form>
    </div>
</div>
@endsection
