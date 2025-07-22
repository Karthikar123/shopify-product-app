@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4"> Synced Shopify Locations</h1>

        {{-- Sync Button --}}
        <div class="mb-3 text-end">
            <a href="{{ route('locations.sync.now') }}" class="btn btn-primary px-4 py-2">
                🔄 Sync Locations
            </a>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Locations Table --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Location Name</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($locations as $location)
                        <tr>
                            <td>{{ $location->location_id }}</td>
                            <td>{{ $location->name }}</td>
                            <td>{{ $location->address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No locations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
