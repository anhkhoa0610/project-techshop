<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('product_name', 255);
            $table->text('description');
            $table->integer('stock_quantity')->default(0);
            $table->decimal('price', 10, 0);
            $table->string('cover_image', 255)->nullable();
            $table->integer('volume_sold')->default(0);
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('supplier_id');
            $table->integer('warranty_period');
            $table->date('release_date');
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('cascade');
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
