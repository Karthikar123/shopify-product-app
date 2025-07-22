<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the 'products' table for storing Shopify product data.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->bigInteger('shopify_product_id')->unique(); // Shopify's product ID
            $table->string('title'); // Product title
            $table->decimal('price', 10, 2)->nullable(); // Product price with precision
            $table->json('metafields')->nullable(); // JSON field for metafields data
            $table->string('image_url')->nullable(); // URL of product image
            $table->integer('inventory_quantity')->nullable(); // Inventory quantity
            $table->string('sku')->nullable(); // Stock Keeping Unit
            $table->longText('body_html')->nullable(); // Product description (HTML)
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     * Drops the 'products' table.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
