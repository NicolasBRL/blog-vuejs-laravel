<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "pseudo" => "administrateur",
            "password" => Hash::make('admin'),
            "email" => "admin@example.com",
            "email_verified_at" => now(),
            "remember_token" => Str::random(10),
            "role_id" => 2
        ]);


        User::create([
            "pseudo" => "user",
            "password" => Hash::make('admin'),
            "email" => "user@example.com",
            "email_verified_at" => now(),
            "remember_token" => Str::random(10),
            "role_id" => 1
        ]);

        User::factory(8)->create();
    }
}
