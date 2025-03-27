<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create standard Lao college semesters (typically 6-8 semesters for bachelor's degree)
        $semesters = [
            'ພາກຮຽນທີ 1',  // Semester 1
            'ພາກຮຽນທີ 2',  // Semester 2 
            'ພາກຮຽນທີ 3',  // Semester 3
            'ພາກຮຽນທີ 4',  // Semester 4
            'ພາກຮຽນທີ 5',  // Semester 5
            'ພາກຮຽນທີ 6',  // Semester 6
            'ພາກຮຽນທີ 7',  // Semester 7
            'ພາກຮຽນທີ 8',  // Semester 8
        ];
        
        foreach ($semesters as $semesterName) {
            Semester::create(['name' => $semesterName]);
        }
    }
}
