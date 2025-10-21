<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
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
        $faker = Factory::create('vi_VN');
        $emailDomains = ['@gmail.com', '@outlook.com', '@hotmail.com', '@mail.tdc.edu.vn'];

        $ho = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Võ', 'Đặng', 'Bùi', 'Đỗ', 'Lương', 'Lý', 'Lâm', 'Lê', 'Đinh'];
        $ten = ['An', 'Bình', 'Chi', 'Dũng', 'Hằng', 'Khoa', 'Lan', 'Minh', 'Ngọc', 'Thảo', 'Tùng', 'Vân', 'Kiệt', 'Hùng', 'Hạnh', 'Yến', 'Hương', 'Hải', 'Huy'];

        for ($i = 0; $i < 50; $i++) {
            $fullName = $faker->randomElement($ho) . ' ' . $faker->firstName . ' ' . $faker->randomElement($ten);
            $emailName = strtolower(str_replace(' ', '.', $faker->userName()));
            $domain = $faker->randomElement($emailDomains);
            $email = $emailName . $domain;

            $isTDC = str_ends_with($email, '@mail.tdc.edu.vn') ? 'true' : 'false';

            User::create([
                'full_name' => $fullName,
                'email' => $email,
                'phone' => '09' . rand(10000000, 99999999),
                'password' => Hash::make('123456'),
                'address' => $faker->address(),
                'role' => $faker->randomElement(['User', 'Admin']),
                'birth' => $faker->date('Y-m-d', '2005-01-01'),
                'is_tdc_student' => $isTDC,
            ]);
        }
    }
}
    
    // public function run(): void
    // {
    //     DB::table('users')->insert([
    //         [
    //             'full_name' => 'Nguyễn Văn A',
    //             'phone' => '0901234567',
    //             'email' => 'admin@example.com',
    //             'password' => Hash::make('123456'),
    //             'address' => '123 Đường ABC, TP.HCM',
    //             'role' => 'Admin',
    //             'birth' => '1990-01-01',
    //             'is_tdc_student' => 'false',
    //         ],
    //         [
    //             'full_name' => 'Trần Thị B',
    //             'phone' => '0912345678',
    //             'email' => 'user@example.com',
    //             'password' => Hash::make('123456'),
    //             'address' => '456 Đường XYZ, TP.HCM',
    //             'role' => 'User',
    //             'birth' => '2000-05-10',
    //             'is_tdc_student' => 'true',
    //         ]
    //     ]);
    // }

