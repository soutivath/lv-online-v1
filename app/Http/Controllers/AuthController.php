<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // If already logged in, redirect based on role
        if (Session::has('user')) {
            return $this->redirectBasedOnUserType(Session::get('user'));
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()
                ->withErrors(['email' => 'Invalid credentials'])
                ->withInput($request->except('password'));
        }

        // Load user relationships to determine role
        $user->load(['student', 'employee']);
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ];

        // Store user data in session
        Session::put('user', $userData);

        return $this->redirectBasedOnUserType($user)
            ->with('sweet_alert', [
                'type' => 'success',
                'title' => 'Success!',
                'text' => 'You have been logged in successfully.'
            ]);
    }

    /**
     * Redirect user based on their type (student or employee)
     */
    private function redirectBasedOnUserType($user)
    {
        
        // If user is passed as array (from session), check if it contains student relation
        if (is_array($user)) {
            // We need to retrieve the actual user to check relationships
            $actualUser = User::with(['student', 'employee'])->find($user['id']);
            if ($actualUser && $actualUser->employee) {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route(
                    'main');
            }
        }
      
        // If user is passed as model instance
        if ($user->student) {
            return redirect()->route('main');
        } else if ($user->employee) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('main');
        }
    }

    public function showRegisterForm()
    {
        // If already logged in, redirect to dashboard
        if (Session::has('user')) {
            return $this->redirectBasedOnUserType(Session::get('user'));
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Store user data in session
        Session::put('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);

        return redirect()->route('home')
            ->with('sweet_alert', [
                'type' => 'success',
                'title' => 'Success!',
                'text' => 'Your account has been created successfully.'
            ]);
    }

    public function logout(Request $request)
    {
        // Clear all user session data
        $request->session()->forget('user');
        $request->session()->forget('auth_user');

        // If you're using Laravel's built-in authentication as well
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        // Redirect to home page with success message
        return redirect('/')->with('sweet_alert', [
            'type' => 'success',
            'title' => 'ອອກຈາກລະບົບສຳເລັດ',
            'text' => 'ທ່ານໄດ້ອອກຈາກລະບົບສຳເລັດແລ້ວ'
        ]);
    }
}
