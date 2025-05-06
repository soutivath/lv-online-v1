<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Employee;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\Upgrade;
use App\Models\Major;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $studentCount = Student::count();
        $employeeCount = Employee::count();
        $registrationCount = Registration::count();
        
        $recentRegistrations = Registration::with(['student', 'employee'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $recentPayments = Payment::with('student')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Payment totals by major
        $paymentTotalsByMajor = Payment::with('major')
            ->where('status', 'success')
            ->get()
            ->groupBy(function($payment) {
                return $payment->major ? $payment->major->name : 'Unknown';
            })
            ->map(function($group) {
                return $group->sum('total_price');
            });

        // Upgrade totals by major
        $upgradeTotalsByMajor = Upgrade::with(['major', 'upgradeDetails'])
            ->where('payment_status', 'success')
            ->get()
            ->groupBy(function($upgrade) {
                return $upgrade->major ? $upgrade->major->name : 'Unknown';
            })
            ->map(function($group) {
                // Sum all upgradeDetails->total_price for each upgrade in the group
                return $group->reduce(function($carry, $upgrade) {
                    return $carry + $upgrade->upgradeDetails->sum('total_price');
                }, 0);
            });

        return view('Dashboard.index', compact(
            'studentCount',
            'employeeCount',
            'registrationCount',
            'recentRegistrations',
            'recentPayments',
            'paymentTotalsByMajor',
            'upgradeTotalsByMajor'
        ));
    }
}
