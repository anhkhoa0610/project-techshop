<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['category_name' => 'Laptop', 'description' => 'Various brands of laptops', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Monitor', 'description' => 'Computer monitors', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
