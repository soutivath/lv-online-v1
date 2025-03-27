<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Major;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
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
        
        // Create 10 payments
        for ($i = 0; $i < 10; $i++) {
            $student = $students->random();
            $major = $majors->random();
            $employee = $employees->random();
            $discount = rand(0, 10); // 0-10% discount
            $baseAmount = $major->tuition->price;
            $discountAmount = ($baseAmount * $discount) / 100;
            $status = $statuses[array_rand($statuses)];
            
            Payment::create([
                'student_id' => $student->id,
                'major_id' => $major->id,
                'employee_id' => $employee->id,
                'date' => Carbon::now()->subDays(rand(1, 60))->format('Y-m-d H:i:s'),
                'detail_price' => $baseAmount,
                'pro' => $discount,
                'total_price' => $baseAmount - $discountAmount,
                'status' => $status,
                'payment_proof' => $status === 'pending' ? 'payment_proofs/sample-receipt.jpg' : null,
            ]);
        }
    }
}
