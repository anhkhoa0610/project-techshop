<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategoriesTableSeeder::class,
            SuppliersTableSeeder::class,
            ProductsTableSeeder::class,
            VoucherSeeder::class,
            OrdersTableSeeder::class,
            OrderDetailSeeder::class,
            ReviewSeeder::class,
            SpecSeeder::class,
        ]);
    }
}
