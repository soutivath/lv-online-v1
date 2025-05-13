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
    public function index(Request $request)
    {
        // Get counts for dashboard stats
        $studentCount = Student::count();
        $employeeCount = Employee::count();
        $majorCount = Major::count();
        $registrationCount = Registration::count();
        $paymentCount = Payment::count();
        
        // Student payment search functionality
        $studentPayments = collect();
        $searchedStudent = null;
        $studentPaymentTotal = 0;
     
        if ($request->has('student_payment_search')) {
           
            $searchTerm = $request->input('student_payment_search');
            
            // Find student by name, ID or student_id
            $searchedStudent = Student::where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('sername', 'LIKE', "%{$searchTerm}%")
                ->orWhere('id', 'LIKE', "%{$searchTerm}%")
                ->first();
             
            if ($searchedStudent) {
                // Get all payments for this student
                $studentPayments = Payment::where('student_id', $searchedStudent->id)
                    ->with(['major.year', 'major.term', 'major.semester'])
                    ->orderBy('date', 'desc')
                    ->get();
                    
                // Calculate total payments
                $studentPaymentTotal = $studentPayments->sum('total_price');
            }
        }
        
        // Student upgrade search functionality
        $studentUpgrades = collect();
        $searchedUpgradeStudent = null;
        $studentUpgradeTotal = 0;
        
        if ($request->has('student_upgrade_search')) {
            $searchTerm = $request->input('student_upgrade_search');
            
            // Find student by name, ID or student_id
            $searchedUpgradeStudent = Student::where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('sername', 'LIKE', "%{$searchTerm}%")
                ->orWhere('id', 'LIKE', "%{$searchTerm}%")
                ->first();
                
            if ($searchedUpgradeStudent) {
                // Get all upgrades for this student
                $studentUpgrades = Upgrade::where('student_id', $searchedUpgradeStudent->id)
                    ->with(['major.year', 'major.term', 'major.semester', 'upgradeDetails.subject'])
                    ->orderBy('date', 'desc')
                    ->get();
                    
                // Calculate total from upgrade details
                $studentUpgradeTotal = $studentUpgrades->sum(function ($upgrade) {
                    return $upgrade->upgradeDetails->sum('total_price');
                });
            }
        }
        
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
        
        // Get total payments by major name (grouped)
        $paymentsByMajor = Payment::where('status', 'success')
            ->join('majors', 'payments.major_id', '=', 'majors.id')
            ->select('majors.name as major_name', 
                    DB::raw('GROUP_CONCAT(DISTINCT majors.id) as major_ids'),
                    DB::raw('SUM(payments.total_price) as total_amount'), 
                    DB::raw('COUNT(distinct payments.student_id) as student_count'))
            ->groupBy('majors.name')
            ->get()
            ->map(function($item) {
                // Extract the first major_id to use for the view link
                $majorIds = explode(',', $item->major_ids);
                $item->major_id = $majorIds[0];
                return $item;
            });
        
        // Get total upgrades by major name (grouped)
        $upgradesByMajor = DB::table('upgrades')
            ->join('upgrade_details', 'upgrades.id', '=', 'upgrade_details.upgrade_id')
            ->join('subjects', 'upgrade_details.subject_id', '=', 'subjects.id')
            ->join('majors', 'upgrades.major_id', '=', 'majors.id')
            ->where('upgrades.payment_status', 'success')
            ->select('majors.name as major_name', 
                    DB::raw('GROUP_CONCAT(DISTINCT majors.id) as major_ids'),
                    DB::raw('SUM(upgrade_details.total_price) as total_amount'),
                    DB::raw('COUNT(distinct upgrades.student_id) as student_count'))
            ->groupBy('majors.name')
            ->get()
            ->map(function($item) {
                // Extract the first major_id to use for the view link
                $majorIds = explode(',', $item->major_ids);
                $item->major_id = $majorIds[0];
                return $item;
            });
        
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
            'recentPayments',
            'searchedStudent',
            'studentPayments',
            'studentPaymentTotal',
            'searchedUpgradeStudent',
            'studentUpgrades',
            'studentUpgradeTotal'
        ));
    }

    /**
     * Show detailed payments for a specific major
     */
    public function majorPaymentDetails($majorId)
    {
        $major = Major::findOrFail($majorId);
        
        // Get all majors with the same name to include them in the report
        $relatedMajorIds = Major::where('name', $major->name)->pluck('id')->toArray();
        
        $payments = Payment::whereIn('major_id', $relatedMajorIds)
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
            'studentCount',
            'relatedMajorIds'
        ));
    }

    /**
     * Show detailed upgrades for a specific major
     */
    public function majorUpgradeDetails($majorId)
    {
        $major = Major::findOrFail($majorId);
        
        // Get all majors with the same name to include them in the report
        $relatedMajorIds = Major::where('name', $major->name)->pluck('id')->toArray();
        
        $upgrades = Upgrade::whereIn('major_id', $relatedMajorIds)
            ->where('payment_status', 'success')
            ->with(['student', 'major.semester', 'major.term', 'major.year', 'upgradeDetails.subject'])
            ->orderBy('date', 'desc')
            ->get();
        
        // Calculate total amount from upgrade details
        $totalAmount = 0;
        foreach ($upgrades as $upgrade) {
            $totalAmount += $upgrade->upgradeDetails->sum('price');
        }
        
        $studentCount = $upgrades->pluck('student_id')->unique()->count();
        
        return view('Dashboard.reports.major-upgrades', compact(
            'major', 
            'upgrades', 
            'totalAmount', 
            'studentCount',
            'relatedMajorIds'
        ));
    }
}
