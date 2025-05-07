<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Student;
use App\Models\User;

class MainController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!Session::has('user')) {
            return redirect()->route('login');
        }
        
        // Get user data and determine if student
        $userData = Session::get('user');
       
        $user = User::with('employee')->find($userData['id']);
        
        if (!$user || !$user->employee) {
            return redirect()->route('home');
        }
        
        
        // Get student data
        $student = Student::where('user_id', $user->id)->with([
            'registrations.registrationDetails.major', 
            'payments', 
            'upgrades'
        ])->first();
        
        if (!$student) {
            Session::forget('user');
            return redirect()->route('login')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'text' => 'Student account not found.'
                ]);
        }
        
        return view('main.index', compact('student'));
    }
}
