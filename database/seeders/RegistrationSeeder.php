<?php

namespace Database\Seeders;

use App\Models\Registration;
use App\Models\RegistrationDetail;
use App\Models\Student;
use App\Models\Major;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $majors = Major::all();
        $employees = Employee::all();
        $statuses = ['pending', 'success'];
        
        // Create 10 registrations
        for ($i = 0; $i < 10; $i++) {
            $student = $students->random();
            $employee = $employees->random();
            $discount = rand(0, 15); // 0-15% discount
            $status = $statuses[array_rand($statuses)];
            
            $registration = Registration::create([
                'student_id' => $student->id,
                'employee_id' => $employee->id,
                'date' => Carbon::now()->subDays(rand(1, 90))->format('Y-m-d H:i:s'),
                'pro' => $discount,
                'payment_status' => $status,
                'payment_proof' => $status === 'pending' ? 'registration_proofs/sample-receipt.jpg' : null,
            ]);
            
            // Select 1-2 random majors per registration
            $registrationMajors = $majors->random(rand(1, 2));
            
            foreach ($registrationMajors as $major) {
                $tuitionFee = $major->tuition->price;
                $discountAmount = ($tuitionFee * $discount) / 100;
                
                RegistrationDetail::create([
                    'registration_id' => $registration->id,
                    'major_id' => $major->id,
                    'detail_price' => $tuitionFee,
                    'total_price' => $tuitionFee - $discountAmount,
                ]);
            }
        }
    }
}
