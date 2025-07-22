<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>

    <!-- Bootstrap 5 CDN for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5 px-3">
    <div class="container">
        <h2 class="mb-4">🛠️ Product Admin - Edit Product</h2>

        <!-- Display success message if product is updated -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Edit Product Form -->
        <!-- Submits to the route products.update with product ID -->
        <form method="POST" action="{{ route('products.update', $product->id) }}">
            @csrf
            @method('PUT') <!-- Laravel method spoofing for PUT -->

            <!-- Product Title Input -->
            <div class="mb-3">
                <label for="title" class="form-label">Product Title</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $product->title }}">
            </div>

            <!-- Product Price Input -->
            <div class="mb-3">
                <label for="price" class="form-label">Product Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $product->price }}">
            </div>

            <!-- Metafields Section -->
            <h5 class="mt-4 mb-3">Metafields</h5>

            <div class="row g-3">
                @php
                    // Extract metafields array from product object
                    $metafields = $product->metafields ?? [];
                @endphp

                <!-- Brand Origin Metafield -->
                <div class="col-md-6">
                    <label for="origin" class="form-label">Brand Origin</label>
                    <input type="text" class="form-control" id="origin" name="metafields[origin]" value="{{ $metafields['origin'] ?? '' }}">
                </div>

                <!-- Feature Tags Metafield -->
                <div class="col-md-6">
                    <label for="feature" class="form-label">Feature Tags</label>
                    <input type="text" class="form-control" id="feature" name="metafields[feature]" value="{{ $metafields['feature'] ?? '' }}">
                </div>

                <!-- Care Guide Metafield -->
                <div class="col-md-6">
                    <label for="care_guide" class="form-label">Care Guide</label>
                    <input type="text" class="form-control" id="care_guide" name="metafields[care_guide]" value="{{ $metafields['care_guide'] ?? '' }}">
                </div>

                <!-- Fabric Care Instructions Metafield -->
                <div class="col-md-6">
                    <label for="fabric_care" class="form-label">Fabric Care Instructions</label>
                    <input type="text" class="form-control" id="fabric_care" name="metafields[fabric_care]" value="{{ $metafields['fabric_care'] ?? '' }}">
                </div>

                <!-- Color Pattern Metafield -->
                <div class="col-md-6">
                    <label for="color-pattern" class="form-label">Color Pattern</label>
                    <input type="text" class="form-control" id="color-pattern" name="metafields[color-pattern]" value="{{ $metafields['color-pattern'] ?? '' }}">
                </div>

                <!-- Target Gender Metafield -->
                <div class="col-md-6">
                    <label for="target-gender" class="form-label">Target Gender</label>
                    <input type="text" class="form-control" id="target-gender" name="metafields[target-gender]" value="{{ $metafields['target-gender'] ?? '' }}">
                </div>
            </div>

            <!-- Submit and Back Button -->
            <button type="submit" class="btn btn-primary mt-4">Update Product</button>
            <a href="{{ url('/products') }}" class="btn btn-secondary mt-4">Back to List</a>
        </form>
    </div>
</body>
</html>
