<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo admin accounts
        Admin::create([
            'name' => 'Demo Admin',
            'email' => 'admin@demo.com',
            'password' => Hash::make('demo123'),
        ]);

        Admin::create([
            'name' => 'Manager Demo',
            'email' => 'manager@demo.com',
            'password' => Hash::make('demo123'),
        ]);

        // Create demo user accounts
        User::create([
            'name' => 'Demo User',
            'email' => 'user@demo.com',
            'password' => Hash::make('demo123'),
        ]);

        User::create([
            'name' => 'Customer Demo',
            'email' => 'customer@demo.com',
            'password' => Hash::make('demo123'),
        ]);

        $this->command->info('Demo accounts created successfully!');
        $this->command->info('Admin accounts: admin@demo.com, manager@demo.com (password: demo123)');
        $this->command->info('User accounts: user@demo.com, customer@demo.com (password: demo123)');
    }
} 