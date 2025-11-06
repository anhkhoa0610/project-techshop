<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/..._create_posts_table.php
public function up(): void
{
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // Tiêu đề
        $table->text('description')->nullable(); // Mô tả ngắn
        $table->longText('content')->nullable(); // Nội dung chi tiết (HTML)
        $table->string('source_url')->unique(); // Link bài gốc (quan trọng để tránh trùng lặp)
        $table->string('cover_image')->nullable(); // Thêm cột mới
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
