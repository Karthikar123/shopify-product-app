<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migration to add a new column `shopify_variant_id`
     * to the `products` table. This column stores the variant ID
     * from Shopify and is placed after `shopify_product_id`.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('shopify_variant_id')->nullable()->after('shopify_product_id');
        });
    }

    /**
     * Revert the migration by removing the `shopify_variant_id` column
     * from the `products` table.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('shopify_variant_id');
        });
    }
};
