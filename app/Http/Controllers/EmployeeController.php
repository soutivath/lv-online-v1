<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('user')->get();
        return view('Dashboard.employees.index', compact('employees'));
    }

    public function store(Request $request)
    {

           // First validate the email existence
           $existingUser = User::where('email', $request->email)->first();
           if ($existingUser) {
               return redirect()->back()
                   ->with('sweet_alert', [
                       'type' => 'error',
                       'title' => 'ຜິດພາດ!',
                       'text' => 'ອີເມວນີ້ຖືກນຳໃຊ້ແລ້ວ'
                   ])
                   ->withInput();
           }
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:20',
            'sername' => 'required|string|max:20',
            'birthday' => 'required|date',
            'date' => 'required|date',
            'gender' => 'required|string|max:5',
            'address' => 'required|string|max:50',
            'tell' => 'required|numeric',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|in:admin,teacher',
        ]);

        DB::beginTransaction();
        
        try {
            // Create user account
            $user = new User();
            $user->name = $request->name . ' ' . $request->sername;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            
            // Create employee record
            $employee = new Employee();
            $employee->name = $request->name;
            $employee->sername = $request->sername;
            $employee->birthday = $request->birthday;
            $employee->date = Carbon::parse($request->date)->format('Y-m-d H:i:s');
            $employee->gender = $request->gender;
            $employee->address = $request->address;
            $employee->tell = $request->tell;
            $employee->role = $request->role;
            $employee->user_id = $user->id;
            
            if ($request->hasFile('picture')) {
                $picturePath = $request->file('picture')->store('employees', 'public');
                $employee->picture = $picturePath;
            }
            
            $employee->save();
            
            DB::commit();

            return redirect()->route('employees.index')
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'Success!',
                    'text' => 'Employee and user account created successfully.'
                ]);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'text' => 'Failed to create employee: ' . $e->getMessage()
                ])
                ->withInput();
        }
    }

    public function show(Employee $employee)
    {
        $employee->load('user');
        return view('Dashboard.employees.show', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $rules = [
            'name' => 'required|string|max:20',
            'sername' => 'required|string|max:20',
            'birthday' => 'required|date',
            'date' => 'required|date',
            'gender' => 'required|string|max:5',
            'address' => 'required|string|max:50',
            'tell' => 'required|numeric',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'role' => 'required|string|in:admin,teacher',
        ];
        
        // Add email validation if user exists
        if ($employee->user) {
            $rules['email'] = 'required|string|email|max:255|unique:users,email,' . $employee->user->id;
        } else {
            $rules['email'] = 'required|string|email|max:255|unique:users,email';
            $rules['password'] = 'required|string|min:6|confirmed';
        }
        
        // Add password validation if it's provided
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }
        
        $validatedData = $request->validate($rules);

        DB::beginTransaction();
        
        try {
            // Update or create user account
            if ($employee->user) {
                $user = $employee->user;
                $user->name = $request->name . ' ' . $request->sername;
                $user->email = $request->email;
                
                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                }
                
                $user->save();
            } else {
                $user = new User();
                $user->name = $request->name . ' ' . $request->sername;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->save();
                
                $employee->user_id = $user->id;
            }
            
            // Update employee record
            $employee->name = $request->name;
            $employee->sername = $request->sername;
            $employee->birthday = $request->birthday;
            $employee->date = $request->date;
            $employee->gender = $request->gender;
            $employee->address = $request->address;
            $employee->tell = $request->tell;
            $employee->role = $request->role;
            
            if ($request->hasFile('picture')) {
                if ($employee->picture) {
                    Storage::disk('public')->delete($employee->picture);
                }
                
                $picturePath = $request->file('picture')->store('employees', 'public');
                $employee->picture = $picturePath;
            }
            
            $employee->save();
            
            DB::commit();

            return redirect()->route('employees.index')
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'Success!',
                    'text' => 'Employee and user account updated successfully.'
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'text' => 'Failed to update employee: ' . $e->getMessage()
                ])
                ->withInput();
        }
    }

    public function destroy(Employee $employee)
    {
        DB::beginTransaction();
        
        try {
            // Delete profile picture if exists
            if ($employee->picture) {
                Storage::disk('public')->delete($employee->picture);
            }
            
            // Delete the user account if it exists
            if ($employee->user) {
                $employee->user->delete();
            }
            
            // Delete the employee record (related registrations, payments, and upgrades will be set to null)
            $employee->delete();
            
            DB::commit();

            return redirect()->route('employees.index')
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'Success!',
                    'text' => 'Employee and all related data deleted successfully.'
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('employees.index')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'text' => 'Failed to delete employee: ' . $e->getMessage()
                ]);
        }
    }

    public function exportPDF(Employee $employee)
    {
        $employee->load(['user']);
        
        $data = [
            'employee' => $employee,
            'date' => now()
        ];
        
        $pdf = \PDF::loadView('pdfs.employee', $data);
        return $pdf->download('employee-'.$employee->id.'.pdf');
    }

    public function exportAllPDF()
    {
        $employees = Employee::with(['user'])->get();
        
        $data = [
            'employees' => $employees,
            'date' => now()
        ];
        
        $pdf = \PDF::loadView('pdfs.employees', $data);
        return $pdf->download('all-employees.pdf');
    }
}
