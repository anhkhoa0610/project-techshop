<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Xóa dữ liệu cũ để tránh trùng lặp khi chạy lại seeder
        DB::table('categories')->delete();

        DB::table('categories')->insert([
            [
                'category_name' => 'Luxury Macbook',
                'description' => 'Dòng MacBook sang trọng, hiệu năng đỉnh cao cho giới chuyên nghiệp.', // <-- Viết lại
                'cover_image' => 'mac.png', // <-- Thêm mới
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Gaming Laptop',
                'description' => 'Chiến hạm gaming, cấu hình khủng, thống trị mọi đấu trường ảo.', // <-- Viết lại
                'cover_image' => 'laptop.png', // <-- Thêm mới
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Premium iPhone',
                'description' => 'Biểu tượng của sự sang trọng, tích hợp công nghệ đỉnh cao và trải nghiệm mượt mà.', // <-- Sửa lại
                'cover_image' => 'phone.png', // <-- Đổi seed ảnh
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Tablet & iPad',
                'description' => 'Giải trí đỉnh cao, làm việc đa nhiệm và sáng tạo không giới hạn.', // <-- Viết lại
                'cover_image' => 'ipad.png', // <-- Thêm mới
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Smart Watch',
                'description' => 'Phụ kiện thời thượng, trợ lý sức khỏe và kết nối thông minh trên cổ tay.', // <-- Viết lại
                'cover_image' => 'smartwatch.png', // <-- Thêm mới
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Elegant Airpod',
                'description' => 'Thiết kế tinh tế, âm thanh không dây hoàn hảo, trải nghiệm xuyên suốt.', // <-- Viết lại (sửa từ máy ảnh)
                'cover_image' => 'airpod.png', // <-- Thêm mới
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}