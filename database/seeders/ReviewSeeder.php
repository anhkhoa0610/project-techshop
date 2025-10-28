<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $users = User::all();

        if ($products->count() === 0 || $users->count() === 0) {
            $this->command->info('Không có sản phẩm hoặc người dùng để tạo review.');
            return;
        }

        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 100; $i++) {
            Review::create([
                'product_id' => $products->random()->product_id,
                'user_id' => $users->random()->user_id,
                'rating' => $faker->numberBetween(1, 5),
                'comment' => $faker->sentence(15),
                'review_date' => $faker->dateTimeBetween('-2 years', 'now'),
            ]);
        }

        // $this->command->info(' Đã tạo 100 review mẫu thành công!');
    }
}