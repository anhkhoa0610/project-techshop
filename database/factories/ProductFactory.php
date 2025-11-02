<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\Supplier;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected static $counter = 1; // Biến đếm sản phẩm để tạo số thứ tự

    public function definition(): array
    {
        // Lấy hoặc tạo category và supplier
        $category = Category::inRandomOrder()->first() ?? Category::factory()->create();
        $supplier = Supplier::inRandomOrder()->first() ?? Supplier::factory()->create();

        // Tạo product_name dạng "CategoryName SupplierName SốThứTự"
        $productName = sprintf('%s %s %d', $category->category_name, $supplier->name, self::$counter++);

        return [
            'product_name'    => $productName,
            'description'     => "Sản phẩm {$productName} là lựa chọn chất lượng trong danh mục {$category->category_name}, được phân phối bởi {$supplier->name}.",
            'stock_quantity'  => $this->faker->numberBetween(0, 10),
            'price'           => $this->faker->numberBetween(10, 100) * 100000,
            'cover_image'     => "dell-remove-bg.png",
            'volume_sold'     => $this->faker->numberBetween(0, 500),
            'category_id'     => $category->category_id,
            'supplier_id'     => $supplier->supplier_id,
            'warranty_period' => $this->faker->numberBetween(6, 36),
            'release_date'    => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'embed_url_review' => 'https://www.youtube.com/embed/Pc1P-Xch0YU',
            'created_at'      => now(),
            'updated_at'      => now(),
        ];
    }
}
