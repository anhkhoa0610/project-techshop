<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Illuminate\Support\Str;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {

            $discountType = fake()->randomElement(['percent', 'amount']);
            $discountValue = $discountType === 'percent'
                ? fake()->numberBetween(5, 50)  // 5% - 50% (Thỏa mãn max:100)
                : fake()->numberBetween(10000, 200000); // 10k - 200k (Thỏa mãn min:0.01)

            // --- ĐÃ SỬA LOGIC NGÀY THÁNG ---

            // 1. Tạo ngày bắt đầu (start_date) là từ hôm nay ('now') đến 6 tháng sau.
            //    Điều này thỏa mãn ràng buộc 'after_or_equal:today'.
            $startDate = now();

            // 2. Tạo ngày kết thúc (end_date) là một ngày SAU ngày bắt đầu.
            //    Vì $startDate đã là 'now' hoặc muộn hơn,
            //    nên $endDate (sau $startDate) sẽ tự động thỏa mãn 'after:today'.
            $endDate = fake()->dateTimeBetween($startDate, $startDate->format('Y-m-d H:i:s') . ' +3 months');

            // --- KẾT THÚC SỬA ---

            Voucher::create([
                'code' => strtoupper(Str::random(8)), // Mã ngẫu nhiên
                'discount_type' => $discountType,
                'discount_value' => $discountValue,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => fake()->randomElement(['active', 'inactive']),
            ]);
        }
    }
}
