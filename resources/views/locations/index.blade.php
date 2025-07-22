<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopify Locations</title>
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
        }
    </style>
</head>
<body>

<div class="container">

    {{-- Title --}}
    <h1>Synced Shopify Locations</h1>

    {{-- Sync Button --}}
    <div class="mb-3 text-end">
        <a href="{{ route('locations.sync.now') }}" class="btn btn-primary">
            🔄 Sync Locations
        </a>
    </div>

    {{-- Success/Error Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Locations Table --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-dark">
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
                        <td colspan="3" class="text-center">No locations found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
