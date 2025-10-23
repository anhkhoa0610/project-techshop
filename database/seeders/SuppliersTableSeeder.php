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
            [
                'name' => 'HP',
                'email' => 'support@hp.com',
                'phone' => '0912345678',
                'address' => 'USA',
                'description' => 'Supplier of HP laptops and printers',
                'logo' => 'hp.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lenovo',
                'email' => 'info@lenovo.com',
                'phone' => '0923456789',
                'address' => 'China',
                'description' => 'Supplier of Lenovo devices and services',
                'logo' => 'lenovo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Acer',
                'email' => 'sales@acer.com',
                'phone' => '0934567890',
                'address' => 'Taiwan',
                'description' => 'Supplier of Acer laptops and monitors',
                'logo' => 'acer.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MSI',
                'email' => 'info@msi.com',
                'phone' => '0945678901',
                'address' => 'Taiwan',
                'description' => 'Supplier of MSI gaming laptops and components',
                'logo' => 'msi.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Apple',
                'email' => 'contact@apple.com',
                'phone' => '0956789012',
                'address' => 'USA',
                'description' => 'Supplier of Apple products and accessories',
                'logo' => 'apple.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Samsung',
                'email' => 'info@samsung.com',
                'phone' => '0967890123',
                'address' => 'South Korea',
                'description' => 'Supplier of Samsung electronics and laptops',
                'logo' => 'samsung.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Toshiba',
                'email' => 'support@toshiba.com',
                'phone' => '0978901234',
                'address' => 'Japan',
                'description' => 'Supplier of Toshiba storage devices and laptops',
                'logo' => 'toshiba.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intel',
                'email' => 'info@intel.com',
                'phone' => '0989012345',
                'address' => 'USA',
                'description' => 'Supplier of Intel processors and chips',
                'logo' => 'intel.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gigabyte',
                'email' => 'contact@gigabyte.com',
                'phone' => '0990123456',
                'address' => 'Taiwan',
                'description' => 'Supplier of Gigabyte motherboards and GPUs',
                'logo' => 'gigabyte.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
