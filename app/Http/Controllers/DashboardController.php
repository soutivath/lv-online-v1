<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Employee;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\Upgrade;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get counts for dashboard stats
        $studentCount = Student::count();
        $employeeCount = Employee::count();
        $majorCount = Major::count();
        $registrationCount = Registration::count();
        $paymentCount = Payment::count();
        
        // Get student count by major
        $majorStudentCounts = DB::table('registration_details')
            ->join('majors', 'registration_details.major_id', '=', 'majors.id')
            ->join('registrations', 'registration_details.registration_id', '=', 'registrations.id')
            ->select('majors.name as major_name', DB::raw('COUNT(DISTINCT registrations.student_id) as student_count'))
            ->groupBy('majors.name')
            ->orderBy('student_count', 'desc')
            ->get();
        
        // Get recent registrations
        $recentRegistrations = Registration::with(['student', 'registrationDetails.major'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Get pending payments
        $pendingPayments = Payment::where('status', 'pending')
            ->with(['student', 'major'])
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
            'majorCount',
            'registrationCount',
            'paymentCount',
            'recentRegistrations',
            'pendingPayments',
            'paymentTotalsByMajor',
            'upgradeTotalsByMajor',
            'majorStudentCounts'
        ));
    }
}
