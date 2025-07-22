<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    /**
     * Display a list of all synced locations.
     */
    public function index()
    {
        $locations = Location::all();
        return view('locations.index', compact('locations'));
    }

    /**
     * Fetch locations from Shopify API and store or update them in the database.
     */
    public function syncNow()
    {
        // Send a GET request to the Shopify Locations API
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => env('SHOPIFY_ACCESS_TOKEN'),
        ])->get('https://' . env('SHOPIFY_STORE_DOMAIN') . '/admin/api/2024-04/locations.json');

        // If the response is successful, process and store the data
        if ($response->successful()) {
            $locations = $response->json('locations');

            foreach ($locations as $loc) {
                Location::updateOrCreate(
                    ['location_id' => $loc['id']],
                    [
                        'name' => $loc['name'],
                        'address' => $loc['address1'] ?? '',
                    ]
                );
            }

            return redirect()->route('locations.index')->with('success', 'Locations synced successfully!');
        } else {
            return redirect()->route('locations.index')->with('error', 'Failed to fetch locations from Shopify. Status Code: ' . $response->status());
        }
    }
}
