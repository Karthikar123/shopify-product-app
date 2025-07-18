<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_image_and_inventory_to_products_table.php
public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->string('image_url')->nullable();
        $table->integer('inventory_quantity')->nullable();
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        if (Schema::hasColumn('products', 'image_url')) {
            $table->dropColumn('image_url');
        }
        if (Schema::hasColumn('products', 'inventory_quantity')) {
            $table->dropColumn('inventory_quantity');
        }
    });
}


};
