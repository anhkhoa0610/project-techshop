<?php

namespace Database\Factories;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'order_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'status' => 'pending',
            'shipping_address' => $this->faker->address(),
            'payment_method' => $this->faker->randomElement(['cash', 'card', 'transfer']),
            'voucher_id' => Voucher::inRandomOrder()->first()?->id ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
