<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user and associated employee
        $adminUser = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        
        // Create admin employee record
        Employee::create([
            'name' => 'Admin',
            'sername' => 'User',
            'gender' => 'Male',
            'birthday' => Carbon::now()->subYears(30), // 30 years ago
            'date' => Carbon::now(),
            'tell' => '020123456789',
            'address' => 'Admin Office',
            'user_id' => $adminUser->id
        ]);

        $this->command->info('Admin user and employee created successfully!');

        // Create a regular user account
        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);
        
        $this->command->info('Regular user created successfully!');
    }
}
