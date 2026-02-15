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
        // Create only user@example.com
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User',
                'password' => Hash::make('password'),
                'phone' => '+1234567890',
                'department' => 'General',
                'job_title' => 'User',
                'location' => 'Default',
                'bio' => 'Default user account.',
                'two_factor_enabled' => false,
                'is_invited' => false,
                'password_set_at' => now(),
            ]
        );
    }
} 