<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/products', [ProductController::class, 'showProducts']);

Route::get('/test-shopify', [ProductController::class, 'showProducts']);

Route::get('/import-shopify', [ProductController::class, 'fetchProductsWithMetafields']);
