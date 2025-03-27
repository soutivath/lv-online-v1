<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Employee;
use App\Models\Registration;
use App\Models\Payment;
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
            
        return view('Dashboard.index', compact(
            'studentCount',
            'employeeCount',
            'registrationCount',
            'recentRegistrations',
            'recentPayments'
        ));
    }
}
