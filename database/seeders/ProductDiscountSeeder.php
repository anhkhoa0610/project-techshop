<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductDiscount;
use Carbon\Carbon;

class ProductDiscountSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::take(24)->get();

        foreach ($products as $product) {
            // Giảm giá ngẫu nhiên 10–30%
            $discountPercent = rand(10, 30);
            $originalPrice = $product->price;
            $salePrice = round($originalPrice * (1 - $discountPercent / 100), 2);

            ProductDiscount::create([
                'product_id' => $product->product_id, // chú ý nếu PK là product_id
                'original_price' => $originalPrice,
                'sale_price' => $salePrice,
                'discount_percent' => $discountPercent,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(1),
            ]);
        }
    }
}
