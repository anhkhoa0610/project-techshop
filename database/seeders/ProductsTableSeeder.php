<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'product_name' => 'Dell Vostro 3550',
                'description' => 'A mid-range business laptop',
                'stock_quantity' => 10,
                'price' => 299.99,
                'cover_image' => 'vostro3550.jpg',
                'volume_sold' => 10,
                'category_id' => 1,
                'supplier_id' => 1,
                'warranty_period' => 24,
                'release_date' => '2020-01-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'Asus K43S',
                'description' => 'A compact laptop for students',
                'stock_quantity' => 15,
                'price' => 399.99,
                'cover_image' => 'asus_k43s.jpg',
                'volume_sold' => 12,
                'category_id' => 1,
                'supplier_id' => 2,
                'warranty_period' => 12,
                'release_date' => '2021-06-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
