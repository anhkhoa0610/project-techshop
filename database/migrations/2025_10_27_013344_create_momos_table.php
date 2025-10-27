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
        Schema::create('momos', function (Blueprint $table) {
        $table->id();
        $table->string('order_id')->unique(); // Mã đơn hàng
        $table->string('request_id')->nullable(); // Mã yêu cầu MoMo
        $table->string('trans_id')->nullable(); // Mã giao dịch MoMo
        $table->string('amount'); // Số tiền
        $table->string('order_info')->nullable(); // Thông tin đơn hàng
        $table->string('result_code')->nullable(); // Mã kết quả từ MoMo (0 = thành công)
        $table->string('message')->nullable(); // Thông điệp phản hồi
        $table->string('pay_url')->nullable(); // Link thanh toán (nếu có)
        $table->string('status')->default('pending'); // pending, success, failed
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('momos');
    }
};
