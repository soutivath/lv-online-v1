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

        // Get total payments
        $totalPayments = Payment::where('status', 'success')->sum('total_price');
        
        // Get total payments by major
        $paymentsByMajor = Payment::where('status', 'success')
            ->select('major_id', DB::raw('SUM(total_price) as total_amount'), DB::raw('COUNT(distinct student_id) as student_count'))
            ->groupBy('major_id')
            ->with('major')
            ->get();
        
        // Get total upgrades by major
        $upgradesByMajor = DB::table('upgrades')
            ->join('upgrade_details', 'upgrades.id', '=', 'upgrade_details.upgrade_id')
            ->join('subjects', 'upgrade_details.subject_id', '=', 'subjects.id')
            ->join('majors', 'upgrades.major_id', '=', 'majors.id')
            ->where('upgrades.payment_status', 'success')
            ->select('majors.id as major_id', 'majors.name as major_name', 
                    DB::raw('SUM(upgrade_details.total_price) as total_amount'),
                    DB::raw('COUNT(distinct upgrades.student_id) as student_count'))
            ->groupBy('majors.id', 'majors.name')
            ->get();
        
        // Recent payments and registrations
        $recentPayments = Payment::with(['student', 'major'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
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
            'majorStudentCounts',
            'totalPayments',
            'paymentsByMajor',
            'upgradesByMajor',
            'recentPayments'
        ));
    }

    /**
     * Show detailed payments for a specific major
     */
    public function majorPaymentDetails($majorId)
    {
        $major = Major::findOrFail($majorId);
        
        $payments = Payment::where('major_id', $majorId)
            ->where('status', 'success')
            ->with(['student', 'major.semester', 'major.term', 'major.year'])
            ->orderBy('date', 'desc')
            ->get();
        
        $totalAmount = $payments->sum('total_price');
        $studentCount = $payments->pluck('student_id')->unique()->count();
        
        return view('Dashboard.reports.major-payments', compact(
            'major', 
            'payments', 
            'totalAmount', 
            'studentCount'
        ));
    }

    /**
     * Show detailed upgrades for a specific major
     */
    public function majorUpgradeDetails($majorId)
    {
        $major = Major::findOrFail($majorId);
        
        $upgrades = Upgrade::where('major_id', $majorId)
            ->where('payment_status', 'success')
            ->with(['student', 'major.semester', 'major.term', 'major.year', 'upgradeDetails.subject'])
            ->orderBy('date', 'desc')
            ->get();
        
        // Calculate total amount from upgrade details
        $totalAmount = 0;
        foreach ($upgrades as $upgrade) {
            $totalAmount += $upgrade->upgradeDetails->sum('total_price');
        }
        
        $studentCount = $upgrades->pluck('student_id')->unique()->count();
        
        return view('Dashboard.reports.major-upgrades', compact(
            'major', 
            'upgrades', 
            'totalAmount', 
            'studentCount'
        ));
    }
}
