<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
      public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('cart_id'); // PRIMARY KEY, AUTO_INCREMENT
            $table->unsignedBigInteger('user_id'); // FOREIGN KEY
            $table->unsignedBigInteger('product_id'); // FOREIGN KEY
            $table->integer('quantity'); // Không cho phép null
            $table->timestamps();
            // Khóa ngoại

            $table->foreign('user_id')
                ->references('user_id')->on('users')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('product_id')->on('products')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
