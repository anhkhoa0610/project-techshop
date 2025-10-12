<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id'); // PRIMARY KEY, AI

            $table->unsignedBigInteger('user_id'); // FOREIGN KEY -> users
            $table->dateTime('order_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])
                ->default('pending');
            $table->string('shipping_address', 255);
            $table->enum('payment_method', ['cash', 'card', 'transfer'])
                ->default('cash');
            $table->unsignedBigInteger('voucher_id')->nullable(); // FOREIGN KEY -> vouchers
            $table->decimal('total_price', 10, 2)->default(0.00);

            // Quan hệ khóa ngoại
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('voucher_id')->references('voucher_id')->on('vouchers')->onDelete('set null');

            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
