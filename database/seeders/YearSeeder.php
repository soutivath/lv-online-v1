<?php

namespace Database\Seeders;

use App\Models\Year;
use Illuminate\Database\Seeder;

class YearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create recent academic years
        $currentYear = date('Y');
        
        for ($i = 0; $i < 5; $i++) {
            $yearStart = $currentYear - $i;
            $yearEnd = $yearStart + 1;
            $academicYear = $yearStart . '-' . $yearEnd;
            
            Year::create(['name' => $academicYear]);
        }
    }
}
