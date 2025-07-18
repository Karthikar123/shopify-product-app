<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/products', [ProductController::class, 'showProducts']);
Route::get('/test-shopify', [ProductController::class, 'showProducts']);

// 👉 Add this to trigger the product import
Route::get('/import-shopify', [ProductController::class, 'fetchProductsWithMetafields']);
