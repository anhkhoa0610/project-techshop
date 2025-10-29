<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ProductImage;

class ProductsTableSeeder extends Seeder
{
    public function run(): void
    {
        Product::factory()->count(100)->create();
        $products = Product::all();
        foreach ($products as $product) {
            foreach (range(1, 5) as $i) {
                ProductImage::factory()->create([
                    'product_id' => $product->product_id,
                    'image_name' => "product_details{$i}.jpg",
                ]);
            }
        }
    }
}
