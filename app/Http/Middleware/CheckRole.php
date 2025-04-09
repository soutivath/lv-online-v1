<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is logged in
        if (!Session::has('user')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            }
            
            return redirect()->route('login')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'Unauthorized!',
                    'text' => 'Please log in to access this page.'
                ]);
        }

        // Get user data from session
        // $userData = Session::get('user');
        // $user = User::with(['student', 'employee'])->find($userData['id']);
        
        // if (!$user) {
        //     Session::forget('user');
        //     return redirect()->route('login')
        //         ->with('sweet_alert', [
        //             'type' => 'error', 
        //             'title' => 'Error!',
        //             'text' => 'User not found.'
        //         ]);
        // }
        
        // // Determine user role based on relations
        // $userRole = $user->student ? 'student' : ($user->employee ? 'admin' : 'admin');
        
        // if (!in_array($userRole, $roles)) {
        //     if ($request->ajax() || $request->wantsJson()) {
        //         return response('Forbidden.', 403);
        //     }
            
        //     // If student accessing admin area, redirect to main
        //     // If admin accessing student area, redirect to dashboard
        //     return redirect()->route($userRole === 'student' ? 'main' : 'dashboard')
        //         ->with('sweet_alert', [
        //             'type' => 'error',
        //             'title' => 'Access Denied!',
        //             'text' => 'You do not have permission to access this page.'
        //         ]);
        // }
        
        return $next($request);
    }
}
