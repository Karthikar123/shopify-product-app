<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migration to add the `is_edited` column to the `products` table.
     * This column is a boolean flag (default: false) used to mark if a product
     * was manually edited in the local system (so it won't be overwritten by sync).
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_edited')->default(false);
        });
    }

    /**
     * Reverse the migration by dropping the `is_edited` column
     * from the `products` table.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_edited');
        });
    }
};
