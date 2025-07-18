<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'shopify_product_id',
        'title',
        'price',
        'metafields',
        'image_url',
        'inventory_quantity',
        'sku',
        'body_html',
    ];

    protected $casts = [
        'metafields' => 'array',
    ];
}
