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
                ? fake()->numberBetween(5, 50)   // 5% - 50%
                : fake()->numberBetween(10000, 200000); // 10k - 200k

            $startDate = fake()->dateTimeBetween('2025-01-01', '2025-06-01');
            $endDate = fake()->dateTimeBetween($startDate, '2025-12-31');

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
