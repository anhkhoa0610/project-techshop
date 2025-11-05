<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'category_name' => 'The Best Smartphone',
                'description' => 'Điện thoại cao cấp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Gaming Laptop',
                'description' => 'Laptop chuyên game',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Premium Headphone',
                'description' => 'Tai nghe chất lượng cao',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Tablet & iPad',
                'description' => 'Máy tính bảng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Smart Watch',
                'description' => 'Đồng hồ thông minh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Camera & Photo',
                'description' => 'Máy ảnh chuyên nghiệp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

}
