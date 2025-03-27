<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use PDF;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('user')->get();
        return view('Dashboard.students.index', compact('students'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'sername' => 'required|string|max:255',
            'gender' => 'required|string|max:50',
            'birthday' => 'required|date',
            'nationality' => 'required|string|max:100',
            'tell' => 'required|string|max:50',
            'address' => 'required|string',
            'picture' => 'nullable|image|max:2048',
            'score' => 'nullable|image|max:2048', // Changed to only accept images
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        DB::beginTransaction();

        try {
            // Create user account first
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // Create student record
            $student = new Student();
            $student->user_id = $user->id;
            $student->name = $validatedData['name'];
            $student->sername = $validatedData['sername'];
            $student->gender = $validatedData['gender'];
            $student->birthday = $validatedData['birthday'];
            $student->nationality = $validatedData['nationality'];
            $student->tell = $validatedData['tell'];
            $student->address = $validatedData['address'];

            // Handle picture upload if present
            if ($request->hasFile('picture')) {
                $student->picture = $request->file('picture')->store('student_pictures', 'public');
            }
            
            // Handle score upload if present
            if ($request->hasFile('score')) {
                $student->score = $request->file('score')->store('student_scores', 'public');
            }

            $student->save();

            DB::commit();

            return redirect()->route('students.index')
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'Success!',
                    'text' => 'Student created successfully.'
                ]);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'text' => 'Failed to create student. Error: ' . $e->getMessage()
                ]);
        }
    }

    public function show(Student $student)
    {
        $student->load('user');
        return view('Dashboard.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        // Make sure the student and their user relationship are loaded
        $student->load('user');
        
        // Debug document_score field before displaying
        \Log::info('Student document_score value:', ['value' => $student->document_score]);
        
        return view('Dashboard.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $validationRules = [
            'name' => 'required|string|max:255',
            'sername' => 'required|string|max:255',
            'gender' => 'required|string|max:50',
            'birthday' => 'required|date',
            'nationality' => 'required|string|max:100',
            'tell' => 'required|string|max:50',
            'address' => 'required|string',
            'picture' => 'nullable|image|max:2048',
            'score' => 'nullable|image|max:2048', // Changed to only accept images
        ];

        // If email is changed, ensure it's unique
        if ($student->user && $request->email !== $student->user->email) {
            $validationRules['email'] = 'required|email|unique:users,email';
        } else {
            $validationRules['email'] = 'required|email';
        }

        // Only validate password if provided
        if ($request->filled('password')) {
            $validationRules['password'] = 'string|min:6';
        }

        $validatedData = $request->validate($validationRules);

        DB::beginTransaction();

        try {
            $student->name = $validatedData['name'];
            $student->sername = $validatedData['sername'];
            $student->gender = $validatedData['gender'];
            $student->birthday = $validatedData['birthday'];
            $student->nationality = $validatedData['nationality'];
            $student->tell = $validatedData['tell'];
            $student->address = $validatedData['address'];

            // Handle picture upload if present
            if ($request->hasFile('picture')) {
                // Delete old picture if exists
                if ($student->picture) {
                    Storage::disk('public')->delete($student->picture);
                }
                $student->picture = $request->file('picture')->store('student_pictures', 'public');
            }
            
            // Handle score upload if present
            if ($request->hasFile('score')) {
                // Delete old score if exists
                if ($student->score) {
                    Storage::disk('public')->delete($student->score);
                }
                $student->score = $request->file('score')->store('student_scores', 'public');
                
                // Log the updated document path
                \Log::info('Updated score:', ['new_path' => $student->score]);
            }

            $student->save();

            // Update or create user if necessary
            if ($validatedData['email']) {
                if ($student->user) {
                    // Update existing user
                    $user = $student->user;
                    $user->name = $validatedData['name'];
                    $user->email = $validatedData['email'];
                    
                    if ($request->filled('password')) {
                        $user->password = Hash::make($request->password);
                    }
                    
                    $user->save();
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $validatedData['name'],
                        'email' => $validatedData['email'],
                        'password' => Hash::make($request->password ?? 'password123'),
                    ]);
                    
                    $student->user_id = $user->id;
                    $student->save();
                }
            }

            DB::commit();

            return redirect()->route('students.show', $student->id)
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'Success!',
                    'text' => 'Student updated successfully.'
                ]);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'text' => 'Failed to update student. Error: ' . $e->getMessage()
                ]);
        }
    }

    public function destroy(Student $student)
    {
        DB::beginTransaction();
        
        try {
            // Delete associated files if they exist
            if ($student->picture) {
                Storage::disk('public')->delete($student->picture);
            }
            
            if ($student->score) {
                Storage::disk('public')->delete($student->score);
            }
            
            // Delete the user account if it exists
            if ($student->user) {
                $student->user->delete();
            }
            
            // Delete the student record (related registrations, payments, and upgrades will be cascaded)
            $student->delete();
            
            DB::commit();
            
            return redirect()->route('students.index')
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'Success!',
                    'text' => 'Student and all related data deleted successfully.'
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('students.index')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'text' => 'Failed to delete student: ' . $e->getMessage()
                ]);
        }
    }

    public function exportPDF(Student $student)
    {
        $student->load(['user', 'registrations.registrationDetails', 'payments', 'upgrades']);
        
        $data = [
            'student' => $student,
            'date' => now()
        ];
        
        $pdf = \PDF::loadView('pdfs.student', $data);
        return $pdf->download('student-'.$student->id.'.pdf');
    }
    
    public function exportAllPDF()
    {
        $students = Student::with(['user'])->get();
        
        $data = [
            'students' => $students,
            'date' => now()
        ];
        
        $pdf = \PDF::loadView('pdfs.students', $data);
        return $pdf->download('all-students.pdf');
    }
}
