<?php

namespace Database\Seeders;

use App\Models\Tuition;
use Illuminate\Database\Seeder;

class TuitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create realistic tuition fees for different program levels
        $tuitionFees = [
            3500000,    // Certificate program - 3,500,000 LAK
            5000000,    // Diploma program - 5,000,000 LAK
            7500000,    // Bachelor's program - 7,500,000 LAK
            9000000,    // Higher diploma - 9,000,000 LAK
            12000000,   // Master's program - 12,000,000 LAK
        ];
        
        foreach ($tuitionFees as $price) {
            Tuition::create(['price' => $price]);
        }
    }
}
