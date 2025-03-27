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
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@laovieng.edu.la',
            'password' => Hash::make('password'),
        ]);

        // Create 10 regular users for students
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => 'Student ' . $i,
                'email' => 'student' . $i . '@laovieng.edu.la',
                'password' => Hash::make('password'),
            ]);
        }

        // Create 5 employee users
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => 'Employee ' . $i,
                'email' => 'employee' . $i . '@laovieng.edu.la',
                'password' => Hash::make('password'),
            ]);
        }
    }
}
