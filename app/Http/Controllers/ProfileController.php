<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Student;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!Session::has('user')) {
            return redirect()->route('login');
        }
        
        // Get user data
        $userData = Session::get('user');
        $user = User::find($userData['id']);
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Get student data with relevant relationships
        $student = Student::where('user_id', $user->id)->with([
            'registrations.registrationDetails.major.semester.term.year', 
            'payments.major.semester.term.year', 
            'upgrades.major.semester.term.year',
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

        // Set flag to show we're viewing the profile
        Session::put('viewing_profile', true);
        
        return view('student.profile', compact('student'));
    }
}
