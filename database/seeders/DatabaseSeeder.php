<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Core data seeders
        $this->call([
            UserSeeder::class,
            SemesterSeeder::class,
            TermSeeder::class,
            YearSeeder::class,
            CreditSeeder::class,
            TuitionSeeder::class,
        ]);
        
        // Entity seeders that depend on core data
        $this->call([
            StudentSeeder::class,
            EmployeeSeeder::class,
            MajorSeeder::class,
            SubjectSeeder::class,
        ]);
        
        // Transaction seeders that depend on entities
        $this->call([
            RegistrationSeeder::class,
            PaymentSeeder::class,
            UpgradeSeeder::class,
        ]);
    }
}
