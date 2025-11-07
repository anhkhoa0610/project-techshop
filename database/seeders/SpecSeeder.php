<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product; // <-- Thêm
use App\Models\Spec;    // <-- Thêm

class SpecSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Spec::query()->delete();

        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->warn('Không tìm thấy sản phẩm nào. Bạn hãy chạy ProductSeeder trước nhé!');
            return;
        }

        $rams = ['8GB', '16GB', '32GB'];
        $cpus = ['AMD Ryzen 5', 'AMD Ryzen 7', 'i7-14650HX'];
        $storages = ['1TB SSD', '2TB SSD', '3TB SSD'];
        $gpu = ['RTX 3060', 'Intel Iris', 'RTX 4060', 'AMD Radeon'];

        foreach ($products as $product) {
            
            Spec::factory()->create([
                'product_id' => $product->product_id,
                'name' => 'RAM',
                'value' => fake()->randomElement($rams) 
            ]);

            Spec::factory()->create([
                'product_id' => $product->product_id,
                'name' => 'CPU',
                'value' => fake()->randomElement($cpus)
            ]);

            Spec::factory()->create([
                'product_id' => $product->product_id,
                'name' => 'Dung lượng',
                'value' => fake()->randomElement($storages) 
            ]);

            Spec::factory()->create([
                'product_id' => $product->product_id,
                'name' => 'GPU',
                'value' => fake()->randomElement($gpu) 
            ]);
        }
    }
}