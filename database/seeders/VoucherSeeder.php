<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Voucher;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Voucher::create([
            'code' => 'DISCOUNT10',
            'discount_type' => 'percent',
            'discount_value' => 10,
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'status' => 'active',
        ]);

        Voucher::create([
            'code' => 'SAVE50K',
            'discount_type' => 'amount',
            'discount_value' => 50000,
            'start_date' => '2025-01-01',
            'end_date' => '2025-06-30',
            'status' => 'inactive',
        ]);
    }
}
