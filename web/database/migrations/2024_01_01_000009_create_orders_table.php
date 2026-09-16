<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('customer_email');
            $table->enum('payment_method', ['bkash', 'nagad']);
            $table->string('sender_number');
            $table->string('transaction_id');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'verified', 'rejected', 'delivered'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
