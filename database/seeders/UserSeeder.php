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
        // Create Chomba, the regular user (customer)
        User::create([
            'name' => 'Chomba',
            'email' => 'chomba@example.com',
            'password' => Hash::make('password'),
            'role_as' => 0, // User
        ]);

        // Create Dave, the admin user
        User::create([
            'name' => 'Dave',
            'email' => 'dave@example.com',
            'password' => Hash::make('password'),
            'role_as' => 1, // Admin
        ]);
    }
}
