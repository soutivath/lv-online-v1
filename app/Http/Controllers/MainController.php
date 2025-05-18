<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Student;
use App\Models\User;

class MainController extends Controller
{
    public function index(Request $request)
    {
        // Check if user is logged in
        if (!Session::has('user')) {
            return redirect()->route('login');
        }
        
        // Get user data
        $userData = Session::get('user');
       
        $user = User::find($userData['id']);
        
        if (!$user) {
            Session::forget('user');
            return redirect()->route('login');
        }
        
        // Get student data with all relationships needed for both dashboard and profile
        $student = Student::where('user_id', $user->id)->with([
            'registrations.registrationDetails.major.semester', 
            'registrations.registrationDetails.major.term',
            'registrations.registrationDetails.major.year',
            'payments.major.semester', 
            'payments.major.term',
            'payments.major.year', 
            'upgrades.major.semester',
            'upgrades.major.term',
            'upgrades.major.year',
            'upgrades.upgradeDetails.subject'
        ])->first();
        
        if (!$student) {
            Session::forget('user');
            return redirect()->route('login')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ຜິດພາດ!',
                    'text' => 'ບໍ່ພົບບັນຊີນັກສຶກສາ.'
                ]);
        }
        
        // Check if we're viewing the profile tab or the separate profile page
        $view = $request->query('view');
        
        if ($request->route()->getName() === 'student.profile') {
            // If explicitly accessing the student profile route, use the dedicated view
            return view('student.profile', compact('student', 'user'));
        }
        
        // For main route, always use main.index view (with tab functionality)
        return view('main.index', compact('student', 'user'));
    }
}
