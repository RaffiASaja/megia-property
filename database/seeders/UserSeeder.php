<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@intaran.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
            ],
        );

        User::updateOrCreate(
            ['email' => 'raffi@intaran.test'],
            [
                'name' => 'Raffi',
                'password' => Hash::make('password123'),
            ],
        );

        User::updateOrCreate(
            ['email' => 'rayhan@intaran.test'],
            [
                'name' => 'Rayhan',
                'password' => Hash::make('password123'),
            ],
        );

        User::updateOrCreate(
            ['email' => 'staff@intaran.test'],
            [
                'name' => 'Staff Megia',
                'password' => Hash::make('password123'),
            ],
        );
    }
}
