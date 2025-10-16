<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Gắn ngẫu nhiên order và product hợp lệ
            'order_id' => Order::inRandomOrder()->first()?->order_id ?? Order::factory(),
            'product_id' => Product::inRandomOrder()->first()?->product_id ?? Product::factory(),

            // Tạo dữ liệu ngẫu nhiên
            'quantity' => $this->faker->numberBetween(1, 5),
            'unit_price' => $this->faker->randomFloat(2, 50000, 500000),
        ];
    }
}
