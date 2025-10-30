<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\User;


class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            // 'product_id' => Product::factory(), 
            // 'user_id' => User::factory(), 
            // 'rating' => $this->faker->numberBetween(1, 5), // Điểm từ 1-5
            // 'comment' => $this->faker->paragraph(2), // Bình luận ngẫu nhiên
            // 'review_date'=>$this->faker->dateTimeBetween('-2 years', 'now'),
            // 'updated_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
