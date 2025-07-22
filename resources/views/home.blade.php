{{-- Load the base layout --}}
@extends('layouts.app')

{{-- Start the content section --}}
@section('content')
<div class="container py-4">
    {{-- Header Section --}}
    <div class="mb-5">
        <h2 class="fw-bold">🛠️ Admin Control Center</h2>
        <p class="text-muted">Manage your products, locations, and admin settings effortlessly.</p>
    </div>

    {{-- Flash Message Alerts --}}
    @if(session('success'))
        {{-- Display success message --}}
        <div class="alert alert-success shadow-sm rounded-3">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        {{-- Display error message --}}
        <div class="alert alert-danger shadow-sm rounded-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="row g-4">
        {{-- Inventory Management Card --}}
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 bg-white hover-shadow">
                <div class="card-body">
                    {{-- Icon and Title --}}
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary-subtle text-primary rounded-circle p-2 me-3">
                            <i class="bi bi-box-seam fs-4"></i>
                        </div>
                        <h5 class="card-title mb-0">Inventory</h5>
                    </div>
                    {{-- Description --}}
                    <p class="card-text text-muted">View and manage all Shopify products and metafields.</p>
                </div>
                {{-- Action Button --}}
                <div class="card-footer bg-transparent border-top-0">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary w-100">Go to Inventory</a>
                </div>
            </div>
        </div>

        {{-- Locations Management Card --}}
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 bg-white hover-shadow">
                <div class="card-body">
                    {{-- Icon and Title --}}
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success-subtle text-success rounded-circle p-2 me-3">
                            <i class="bi bi-geo-alt fs-4"></i>
                        </div>
                        <h5 class="card-title mb-0">Locations</h5>
                    </div>
                    {{-- Description --}}
                    <p class="card-text text-muted">Manage store addresses, contact details and sync locations.</p>
                </div>
                {{-- Action Button --}}
                <div class="card-footer bg-transparent border-top-0">
                    <a href="{{ route('locations.index') }}" class="btn btn-outline-success w-100">Go to Locations</a>
                </div>
            </div>
        </div>

        {{-- About Section Card --}}
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 bg-white hover-shadow">
                <div class="card-body">
                    {{-- Icon and Title --}}
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info-subtle text-info rounded-circle p-2 me-3">
                            <i class="bi bi-info-circle fs-4"></i>
                        </div>
                        <h5 class="card-title mb-0">About</h5>
                    </div>
                    {{-- Description --}}
                    <p class="card-text text-muted">Learn more about the system and customize the experience.</p>
                </div>
                {{-- Action Button --}}
                <div class="card-footer bg-transparent border-top-0">
                    <a href="{{ url('/about') }}" class="btn btn-outline-info w-100">About This Project</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
