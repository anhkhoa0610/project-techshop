<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('order_details', function (Blueprint $table) {
            $table->id('order_detail_id'); // PRIMARY KEY, AUTO_INCREMENT
            $table->unsignedBigInteger('order_id'); // FOREIGN KEY
            $table->unsignedBigInteger('product_id'); // FOREIGN KEY
            $table->integer('quantity'); // Không cho phép null
            $table->decimal('unit_price', 10, 2); // DECIMAL(10,2), Không cho phép null

            // Khóa ngoại
            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
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
