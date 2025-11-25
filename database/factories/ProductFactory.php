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
        $description = <<<TEXT
        {$productName} là một trong những sản phẩm nổi bật nhất trong danh mục {$category->category_name}.  
        Sản phẩm này được cung cấp độc quyền bởi {$supplier->name}, đảm bảo chất lượng và độ bền cao.  
        Thiết kế hiện đại, tinh tế, phù hợp với nhiều nhu cầu sử dụng khác nhau.  
        {$productName} mang lại hiệu năng ổn định và tiết kiệm năng lượng vượt trội.  
        Các linh kiện được tuyển chọn kỹ lưỡng từ những nhà sản xuất uy tín.  
        Sản phẩm có độ hoàn thiện cao, dễ dàng bảo trì và nâng cấp.  
        Thích hợp cho cả cá nhân và doanh nghiệp nhờ tính linh hoạt trong sử dụng.  
        {$supplier->name} cam kết mang đến chế độ bảo hành và hỗ trợ kỹ thuật tận tâm.  
        Sản phẩm đáp ứng tiêu chuẩn an toàn và thân thiện với môi trường.  
        {$productName} chắc chắn sẽ là lựa chọn lý tưởng cho bạn trong phân khúc {$category->category_name}.
        TEXT;

        // Map category_id to cover image
        $coverImageMap = [
            1 => 'macbook-product.png',
            2 => 'laptop-product.png',
            3 => 'iphone-product.png',
            4 => 'ipad-product.png',
            5 => 'watch-product.png',
            6 => 'airpod-product.png',
        ];

        return [
            'product_name' => $productName,
            'description' => $description,
            'stock_quantity' => $this->faker->numberBetween(0, 10),
            'price' => $this->faker->numberBetween(10, 100) * 100000,
            'cover_image' => $coverImageMap[$category->category_id] ?? 'laptop-product.png',
            'volume_sold' => $this->faker->numberBetween(0, 500),
            'category_id' => $category->category_id,
            'supplier_id' => $supplier->supplier_id,
            'warranty_period' => $this->faker->numberBetween(6, 36),
            'release_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'embed_url_review' => 'https://www.youtube.com/embed/Pc1P-Xch0YU',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
