<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Product model for storing Shopify product data.
 * This model maps to a database table (typically `products`) and handles
 * attributes such as product title, price, metafields, and more.
 */
class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     * These fields can be bulk-filled when creating or updating records.
     */
    protected $fillable = [
        'shopify_product_id',  // ID of the product from Shopify
        'title',               // Title of the product
        'price',               // Price of the product
        'metafields',          // JSON-formatted metafields associated with the product
        'image_url',           // URL of the product's main image
        'inventory_quantity',  // Quantity in stock
        'sku',                 // Stock Keeping Unit
        'body_html',           // Full HTML description of the product
    ];

    /**
     * The attributes that should be cast to native types.
     * Here, `metafields` is cast to an array for easy access and manipulation.
     */
    protected $casts = [
        'metafields' => 'array',
    ];
}
