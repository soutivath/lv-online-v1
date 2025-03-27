<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Credit;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Shorter Lao college subjects
        $subjects = [
            'ພາສາອັງກິດ',               // English 
            'ຄະນິດສາດ',                 // Mathematics
            'ບັນຊີ 1',                  // Accounting 1
            'ເສດຖະສາດ',                 // Economics
            'ເວັບໄຊ',                   // Web Development
            'ໂປຣແກຣມ',                  // Programming
            'ຖານຂໍ້ມູນ',                 // Databases
            'ບໍລິຫານທຸລະກິດ',             // Business Management
            'ການຕະຫຼາດ',                 // Marketing
            'ຄອມພິວເຕີ'                  // Computer
        ];
        
        // Get all credit options
        $credits = Credit::all();
        
        if ($credits->isEmpty()) {
            $this->command->error('No credits found. Please run CreditSeeder first.');
            return;
        }
        
        foreach ($subjects as $subjectName) {
            Subject::create([
                'name' => $subjectName,
                'credit_id' => $credits->random()->id,
            ]);
        }
        
        $this->command->info('Successfully seeded ' . count($subjects) . ' subjects.');
    }
}
