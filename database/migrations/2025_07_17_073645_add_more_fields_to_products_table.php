<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds new columns to the products table if they don't already exist.
     */
    public function up()
    {
        // Get the current columns of the products table
        $existingColumns = Schema::getColumnListing('products');

        Schema::table('products', function (Blueprint $table) use ($existingColumns) {

            // Add image_url column if not present
            if (!in_array('image_url', $existingColumns)) {
                $table->string('image_url')->nullable()->after('title');
            }

            // Add inventory_quantity column if not present
            if (!in_array('inventory_quantity', $existingColumns)) {
                $table->integer('inventory_quantity')->nullable()->after('image_url');
            }

            // Add sku column if not present
            if (!in_array('sku', $existingColumns)) {
                $table->string('sku')->nullable()->after('inventory_quantity');
            }

            // Add body_html column if not present
            if (!in_array('body_html', $existingColumns)) {
                $table->text('body_html')->nullable()->after('sku');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the added columns from the products table.
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['image_url', 'inventory_quantity', 'sku', 'body_html']);
        });
    }
};
