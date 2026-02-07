<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Profile;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->truncate();

        for ($i = 1; $i <= 15; $i++) {
            User::create([
                'id' => $i,
                'name' => fake()->name(),
                'email' => "test{$i}@email.com",
                'password' => Hash::make('password'),
            ]);
            Profile::create([
                'user_id' => $i,
                'bio' => fake()->sentence(4),
                'profile_picture' => "default/profile-default.png",
            ]);
        }
    }
}
