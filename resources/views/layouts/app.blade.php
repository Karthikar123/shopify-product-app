<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopify Product Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            width: 250px;
            background-color: #212529;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            padding: 20px 0;
            position: fixed;
            height: 100vh;
        }

        .sidebar h4 {
            font-weight: 600;
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.3rem;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: #adb5bd;
            text-decoration: none;
            font-size: 15px;
            transition: background 0.3s, color 0.3s;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .sidebar a:hover, .sidebar a.active {
            background-color: #343a40;
            color: #ffffff;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
            flex-grow: 1;
        }

        .page-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

    {{-- Sidebar --}}
    <div class="sidebar">
        <h4>Admin Panel</h4>
        <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i> Home
        </a>
        <a href="{{ route('products.index') }}" class="{{ request()->is('products*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Inventory
        </a>
        <a href="{{ route('locations.index') }}" class="{{ request()->is('locations') ? 'active' : '' }}">
            <i class="bi bi-geo-alt"></i> Locations
        </a>
        <a href="{{ url('/about') }}" class="{{ request()->is('about') ? 'active' : '' }}">
            <i class="bi bi-info-circle"></i> About Us
        </a>
    </div>

    {{-- Main Content --}}
    <div class="main-content">
        @yield('content')
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
