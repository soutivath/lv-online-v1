<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Registration;
use App\Models\RegistrationDetail;
use App\Models\Student;
use App\Models\Major;

class PaymentStatusService
{
    /**
     * Check if a specific major has been paid for by a student
     * 
     * @param int $studentId
     * @param int $majorId
     * @return bool
     */
    public function isMajorPaidByStudent($studentId, $majorId)
    {
        // Check direct payments
        $payment = Payment::where('student_id', $studentId)
            ->where('major_id', $majorId)
            ->where('status', 'success')
            ->first();
            
        if ($payment) {
            return true;
        }
        
        // Check if paid through registration
        $registrationDetail = RegistrationDetail::whereHas('registration', function($query) use ($studentId) {
            $query->where('student_id', $studentId)
                  ->where('payment_status', 'success');
        })->where('major_id', $majorId)->first();
        
        return $registrationDetail ? true : false;
    }
    
    /**
     * Get all paid majors for a student
     * 
     * @param int $studentId
     * @return array
     */
    public function getPaidMajorIdsForStudent($studentId)
    {
        // Get majors paid directly
        $paidMajorIdsFromPayments = Payment::where('student_id', $studentId)
            ->where('status', 'success')
            ->pluck('major_id')
            ->toArray();
            
        // Get majors paid through registration
        $paidMajorIdsFromRegistrations = RegistrationDetail::whereHas('registration', function($query) use ($studentId) {
            $query->where('student_id', $studentId)
                  ->where('payment_status', 'success');
        })->pluck('major_id')->toArray();
        
        // Combine and return unique IDs
        return array_unique(array_merge($paidMajorIdsFromPayments, $paidMajorIdsFromRegistrations));
    }
    
    /**
     * Get all majors a student is registered for
     * 
     * @param int $studentId
     * @return array
     */
    public function getRegisteredMajorIdsForStudent($studentId)
    {
        // Get all majors from registrations
        return RegistrationDetail::whereHas('registration', function($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })->pluck('major_id')->unique()->toArray();
    }
}
