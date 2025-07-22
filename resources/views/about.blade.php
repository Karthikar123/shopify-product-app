{{-- resources/views/about.blade.php --}}
{{-- This view displays the "About Us" page for the Shopify Product Admin Panel --}}

@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Centered row for responsive layout --}}
    <div class="row justify-content-center">
        {{-- Main content column with responsive width --}}
        <div class="col-md-10 col-lg-8">
            {{-- Bootstrap card with shadow and rounded borders --}}
            <div class="card shadow-lg border-0 rounded-4">
                {{-- Card header with dark background and white text --}}
                <div class="card-header bg-dark text-white rounded-top-4">
                    <h3 class="mb-0">About Us</h3>
                </div>

                {{-- Card body content with padding --}}
                <div class="card-body p-4">
                    {{-- Introduction text --}}
                    <p class="lead">
                        Welcome to the Shopify Product Admin Panel.
                    </p>

                    {{-- Description of app functionalities --}}
                    <p>
                        This admin dashboard allows you to manage your Shopify product inventory,
                        track location details, and keep your business organized—all in one place.
                    </p>

                    {{-- Divider line --}}
                    <hr>

                    {{-- Developer information --}}
                    <p>
                        Developed by <strong>Emvigotech</strong>, this application ensures seamless integration
                        and control over your Shopify data with real-time syncing and management tools.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
