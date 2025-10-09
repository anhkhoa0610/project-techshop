<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuppliersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('suppliers')->insert([
            [
                'name' => 'Dell Inc.',
                'email' => 'contact@dell.com',
                'phone' => '0123456789',
                'address' => 'USA',
                'description' => 'Official Dell supplier',
                'logo' => 'dell.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Asus',
                'email' => 'info@asus.com',
                'phone' => '0987654321',
                'address' => 'Taiwan',
                'description' => 'Supplier of Asus laptops',
                'logo' => 'asus.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
