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
            'order_id' => Order::query()->inRandomOrder()->value('order_id')
                ?? Order::factory(), // fallback nếu DB chưa có order nào
            'product_id' => Product::query()->inRandomOrder()->value('product_id')
                ?? Product::factory(), // fallback nếu DB chưa có product nào
            'quantity' => $this->faker->numberBetween(1, 5),
            'unit_price' => $this->faker->randomFloat(0, 50000, 500000),
        ];
    }
}
