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
        $createdAt = $this->faker->dateTimeBetween('-1 year', 'now');

        return [
            'user_id' => User::inRandomOrder()->value('user_id'),
            'order_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'status' => $this->faker->randomElement(['cancelled', 'completed', 'processing', 'completed', 'completed', 'completed', 'completed', 'completed']),
            'shipping_address' => $this->faker->address(),
            'payment_method' => $this->faker->randomElement(['momo', 'vnpay', 'cash']),
            'voucher_id' => Voucher::inRandomOrder()->first()?->id ?? null,
            'created_at' => $createdAt,
            'updated_at' => $this->faker->dateTimeBetween($createdAt, 'now'),
        ];
    }
}
