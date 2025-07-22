@extends('layouts.app')

@section('content')
    <h2>Inventory Products</h2>
    <p>Show your products here...</p>
@endsection
@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4 text-primary fw-bold">Inventory Products</h2>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach ($products as $product)
                <div class="col">
                    <div class="card shadow-sm h-100 border-0 rounded-4">
                        <img src="{{ $product->image_url ?? 'https://via.placeholder.com/300x200' }}" class="card-img-top rounded-top-4" alt="Product Image">
                        <div class="card-body">
                            <h5 class="card-title text-dark">{{ $product->title }}</h5>
                            <p class="card-text text-secondary">
                                {{ Str::limit($product->description, 100) }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-success fw-semibold">${{ number_format($product->price, 2) }}</span>
                                <a href="#" class="btn btn-outline-primary btn-sm rounded-pill">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if ($products->isEmpty())
                <div class="col-12">
                    <div class="alert alert-warning text-center" role="alert">
                        No products found in inventory.
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
