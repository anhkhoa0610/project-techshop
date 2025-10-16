<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('orders')->insert([
            [
                'user_id' => 1,
                'order_date' => now(),
                'status' => 'pending',
                'shipping_address' => '123 Lê Lợi, Quận 1, TP.HCM',
                'payment_method' => 'cash',
                'voucher_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'order_date' => now(),
                'status' => 'completed',
                'shipping_address' => '45 Nguyễn Huệ, Quận 1, TP.HCM',
                'payment_method' => 'card',
                'voucher_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'order_date' => now(),
                'status' => 'pending',
                'shipping_address' => '123 Lê Lợi, Quận 1, TP.HCM',
                'payment_method' => 'cash',
                'voucher_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'order_date' => now(),
                'status' => 'completed',
                'shipping_address' => '45 Nguyễn Huệ, Quận 1, TP.HCM',
                'payment_method' => 'card',
                'voucher_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'order_date' => now(),
                'status' => 'pending',
                'shipping_address' => '123 Lê Lợi, Quận 1, TP.HCM',
                'payment_method' => 'cash',
                'voucher_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'order_date' => now(),
                'status' => 'completed',
                'shipping_address' => '45 Nguyễn Huệ, Quận 1, TP.HCM',
                'payment_method' => 'card',
                'voucher_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Cập nhật lại tổng tiền cho các đơn hàng đã tạo
        $orders = Order::all();
        foreach ($orders as $order) {
            $order->updateTotalPrice(); // gọi hàm trong model Order
        }
    }
}
