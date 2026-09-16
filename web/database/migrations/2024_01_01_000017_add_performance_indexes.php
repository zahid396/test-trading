<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_active', 'status', 'sort_order'], 'products_active_status_sort_index');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['is_active', 'is_featured', 'sort_order'], 'reviews_active_featured_sort_index');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'orders_status_created_index');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_active_status_sort_index');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('reviews_active_featured_sort_index');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_status_created_index');
        });
    }
};