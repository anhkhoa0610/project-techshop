<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_details')->insert([
            [
                'order_id' => 1,
                'product_id' => 1,
                'quantity' => 2,
                'unit_price' => 150000.00,
            ],
            [
                'order_id' => 1,
                'product_id' => 2,
                'quantity' => 1,
                'unit_price' => 250000.00,
            ],
            [
                'order_id' => 1,
                'product_id' => 1,
                'quantity' => 3,
                'unit_price' => 120000.00,
            ],
            [
                'order_id' => 1,
                'product_id' => 2,
                'quantity' => 1,
                'unit_price' => 350000.00,
            ],
            [
                'order_id' => 1,
                'product_id' => 1,
                'quantity' => 5,
                'unit_price' => 99000.00,
            ],
            [
                'order_id' => 1,
                'product_id' => 1,
                'quantity' => 5,
                'unit_price' => 99000.00,
            ],
        ]);
         // Cập nhật lại tổng tiền cho các đơn hàng đã tạo
        $orders = Order::all();
        foreach ($orders as $order) {
            $order->updateTotalPrice(); // gọi hàm trong model Order
        }
    }
}
