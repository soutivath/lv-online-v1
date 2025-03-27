<?php

namespace Database\Seeders;

use App\Models\Major;
use App\Models\Semester;
use App\Models\Term;
use App\Models\Year;
use App\Models\Tuition;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Shorter Lao college majors names
        $majors = [
            'ເສດຖະສາດ',          // Economics
            'ບໍລິຫານທຸລະກິດ',      // Business Administration
            'ການບັນຊີ',          // Accounting
            'ວິສະວະກຳ IT',        // IT Engineering (shortened)
            'ວິທະຍາສາດ IT',      // IT Science (shortened)
            'ການທະນາຄານ',        // Banking
            'ພາສາອັງກິດ',         // English Language
            'ວິສະວະກໍາໄຟຟ້າ',      // Electrical Engineering
            'ການຄ້າສາກົນ',        // International Trade
            'ວິສະວະກໍາໂຍທາ',       // Civil Engineering
        ];
        
        // Get all available entities
        $semesters = Semester::all();
        $terms = Term::all();
        $years = Year::all();
        $tuitions = Tuition::all();
        
        if ($semesters->isEmpty() || $terms->isEmpty() || $years->isEmpty() || $tuitions->isEmpty()) {
            $this->command->error('Required seed data missing. Make sure to run SemesterSeeder, TermSeeder, YearSeeder and TuitionSeeder first.');
            return;
        }
        
        foreach ($majors as $index => $majorName) {
            // Generate a random sokhn code
            $prefixes = ['IT', 'BA', 'AC', 'CS', 'CE'];
            $prefix = $prefixes[array_rand($prefixes)];
            $sokhn = sprintf('%s-%03d', $prefix, $index + 1);
            
            Major::create([
                'name' => $majorName,
                'semester_id' => $semesters->random()->id,
                'term_id' => $terms->random()->id,
                'year_id' => $years->random()->id,
                'tuition_id' => $tuitions->random()->id,
                'sokhn' => $sokhn,
            ]);
        }
        
        $this->command->info('Successfully seeded ' . count($majors) . ' majors.');
    }
}
