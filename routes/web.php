<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Services\ShopifyProductService;

// Show all products (renamed to match Laravel convention)
Route::get('/products', [ProductController::class, 'showProducts'])->name('products.index');

// Shopify sync from controller
Route::get('/import-shopify', [ProductController::class, 'syncFromShopify']);
Route::get('/sync-products', [ProductController::class, 'syncFromShopify'])->name('products.sync');

// Edit and update product
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');

// Manual sync trigger via web (using service directly)
Route::get('/sync-now', function () {
    (new ShopifyProductService)->fetchAndStoreProducts();
    return redirect()->route('products.index')->with('success', 'Products synced successfully!');
})->name('products.sync.now');
