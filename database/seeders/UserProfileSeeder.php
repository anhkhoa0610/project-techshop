<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserProfile;
use App\Models\User;
use Faker\Factory as Faker;

class UserProfileSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $users = User::all();

        $avatars = [
            'avatar1.png',
            'avatar2.png',
            'avatar3.png',
            'avatar4.png',
            'avatar5.png',
            'avatar6.png',
            'avatar7.png',
            'avatar8.png',
            'avatar9.png',
        ];

        foreach ($users as $user) {
            // Nếu user đã có profile → bỏ qua
            if ($user->profile) continue;

            UserProfile::create([
                'user_id' => $user->user_id,   
                'avatar' => strtolower($faker->randomElement($avatars)), // FIX: tránh lỗi viết hoa
                'bio' => $faker->sentence(4),
                'website' => $faker->boolean(30) ? $faker->url() : null,
            ]);
        }
        $this->command->info('User profiles seeded successfully!');
    
    }
}