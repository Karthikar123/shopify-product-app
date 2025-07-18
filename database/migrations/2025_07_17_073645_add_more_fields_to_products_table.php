<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $schema = Schema::getColumnListing('products');

            if (!in_array('image_url', $schema)) {
                $table->string('image_url')->nullable();
            }
            if (!in_array('inventory_quantity', $schema)) {
                $table->integer('inventory_quantity')->nullable();
            }
            if (!in_array('sku', $schema)) {
                $table->string('sku')->nullable();
            }
            if (!in_array('body_html', $schema)) {
                $table->text('body_html')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['image_url', 'inventory_quantity', 'sku', 'body_html']);
        });
    }
};
