<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo 50 chi tiết đơn hàng
        OrderDetail::factory(100)->create();
         // Cập nhật lại tổng tiền cho các đơn hàng đã tạo
        $orders = Order::all();
        foreach ($orders as $order) {
            $order->updateTotalPrice(); // gọi hàm trong model Order
        }
    }
}
