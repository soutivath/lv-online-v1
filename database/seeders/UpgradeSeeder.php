<?php

namespace Database\Seeders;

use App\Models\Upgrade;
use App\Models\UpgradeDetail;
use App\Models\Student;
use App\Models\Major;
use App\Models\Subject;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UpgradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $majors = Major::all();
        $subjects = Subject::all();
        $employees = Employee::all();
        $statuses = ['pending', 'success'];
        
        // Create 10 upgrades
        for ($i = 0; $i < 10; $i++) {
            $student = $students->random();
            $major = $majors->random();
            $employee = $employees->random();
            $status = $statuses[array_rand($statuses)];
            
            $upgrade = Upgrade::create([
                'student_id' => $student->id,
                'major_id' => $major->id,
                'employee_id' => $employee->id,
                'date' => Carbon::now()->subDays(rand(1, 45))->format('Y-m-d'),
                'payment_status' => $status,
                'payment_proof' => $status === 'pending' ? 'upgrade_proofs/sample-receipt.jpg' : null,
            ]);
            
            // Add 1-3 subjects to each upgrade
            $upgradeSubjects = $subjects->random(rand(1, 3));
            
            foreach ($upgradeSubjects as $subject) {
                $subjectPrice = $subject->credit->price;
                
                UpgradeDetail::create([
                    'upgrade_id' => $upgrade->id,
                    'subject_id' => $subject->id,
                    'detail_price' => $subjectPrice,
                    'total_price' => $subjectPrice, // No discount on upgrades
                ]);
            }
        }
    }
}
