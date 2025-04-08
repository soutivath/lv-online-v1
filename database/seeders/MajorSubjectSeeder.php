<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Major;
use App\Models\Subject;

class MajorSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            // Example: Attach subjects to majors
            $majors = Major::all();
            $subjects = Subject::all();
    
            foreach ($majors as $major) {
                // Attach random subjects to each major
                // Modify this logic according to your needs
                $subjectsToAttach = $subjects->random(rand(3, 6));
                $major->subjects()->attach($subjectsToAttach);
            }
        
    }
}
