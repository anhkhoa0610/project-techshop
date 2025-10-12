<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'full_name' => 'Nguyễn Văn A',
                'phone' => '0901234567',
                'email' => 'admin@example.com',
                'password' => Hash::make('123456'),
                'address' => '123 Đường ABC, TP.HCM',
                'role' => 'Admin',
                'birth' => '1990-01-01',
                'is_tdc_student' => 'false',
            ],
            [
                'full_name' => 'Trần Thị B',
                'phone' => '0912345678',
                'email' => 'user@example.com',
                'password' => Hash::make('123456'),
                'address' => '456 Đường XYZ, TP.HCM',
                'role' => 'User',
                'birth' => '2000-05-10',
                'is_tdc_student' => 'true',
            ]
        ]);
    }
}
