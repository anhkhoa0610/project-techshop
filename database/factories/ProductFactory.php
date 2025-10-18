<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\Supplier;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Lấy hoặc tạo category và supplier để đảm bảo tồn tại khóa ngoại
        $category = Category::inRandomOrder()->first() ?? Category::factory()->create();
        $supplier = Supplier::inRandomOrder()->first() ?? Supplier::factory()->create();

        return [
            // product_id tự động (id)
            'product_name'    => $this->faker->unique()->words(3, true),
            'description'     => $this->faker->paragraphs(2, true),
            'stock_quantity'  => $this->faker->numberBetween(0, 1000),
            'price'           => $this->faker->randomFloat(2, 1, 10000),
            'cover_image'     => null,
            'volume_sold'     => $this->faker->numberBetween(0, 1000),
            'category_id'     => $category->category_id,
            'supplier_id'     => $supplier->supplier_id,
            'warranty_period' => $this->faker->numberBetween(0, 36),
            'release_date'    => $this->faker->date(),
            'created_at'      => now(),
            'updated_at'      => now(),
        ];
    }
}