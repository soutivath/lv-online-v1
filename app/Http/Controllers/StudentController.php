<?php

namespace App\Http\Controllers;

use App\Http\Controllers\RegistrationController;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;

class StudentController extends Controller
{
    /**
     * Display the student profile information
     * 
     * @return \Illuminate\View\View
     */
    public function profile()
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
        
        return view('student.profile', compact('student'));
    }
    public function index(Request $request)
    {
        $query = Student::query();
        
        // Apply search if a search term is provided
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('id', 'like', "%{$searchTerm}%")
                  ->orWhere('name', 'like', "%{$searchTerm}%")
                  ->orWhere('sername', 'like', "%{$searchTerm}%")
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                      $userQuery->where('email', 'like', "%{$searchTerm}%");
                  });
            });
        }
        
        // Filter by major if provided
        if ($request->has('major') && !empty($request->major)) {
            $majorName = $request->major;
            $query->whereHas('registrations.registrationDetails.major', function($q) use ($majorName) {
                $q->where('name', $majorName);
            })->orWhereHas('payments.major', function($q) use ($majorName) {
                $q->where('name', $majorName);
            });
        }
        
        // Get the results with pagination
        $students = $query->paginate(15);
        
        // Get the major name for display in the view
        $majorFilter = $request->has('major') ? $request->major : null;
        
        return view('Dashboard.students.index', compact('students', 'majorFilter'));
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
            $student->tell = '+85620' . $validatedData['tell'];
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
                    'title' => 'ສຳເລັດ!',
                    'text' => 'ສ້າງນັກສຶກສາສຳເລັດແລ້ວ.'
                ]);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ຜິດພາດ!',
                    'text' => 'ລົ້ມເຫຼວໃນການສ້າງນັກສຶກສາ. ຂໍ້ຜິດພາດ: ' . $e->getMessage()
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
            $student->tell = '+85620' . $validatedData['tell'];
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
                    'title' => 'ສຳເລັດ!',
                    'text' => 'ອັບເດດນັກສຶກສາສຳເລັດແລ້ວ.'
                ]);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ຜິດພາດ!',
                    'text' => 'ລົ້ມເຫຼວໃນການອັບເດດນັກສຶກສາ. ຂໍ້ຜິດພາດ: ' . $e->getMessage()
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
                    'title' => 'ສຳເລັດ!',
                    'text' => 'ລຶບນັກສຶກສາແລະຂໍ້ມູນທີ່ກ່ຽວຂ້ອງທັງໝົດສຳເລັດແລ້ວ.'
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('students.index')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ຜິດພາດ!',
                    'text' => 'ລົ້ມເຫຼວໃນການລຶບນັກສຶກສາ: ' . $e->getMessage()
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

    /**
     * Show the student registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        // Get all data for dropdowns in one go to avoid multiple database queries
        $years = \App\Models\Year::orderBy('name', 'desc')->get();
        $terms = \App\Models\Term::orderBy('name')->get();
        $semesters = \App\Models\Semester::orderBy('name')->get();
        $majors = \App\Models\Major::with(['semester', 'term', 'year'])
            ->orderBy('name')
            ->get();
        
        // Pass all data to the view
        return view('student-registration', compact('years', 'terms', 'semesters', 'majors'));
    }

    public function register(Request $request)
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
            $student->tell = '+85620' . $validatedData['tell'];
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

       

            return $student;
       
    }


    public function updatedRegistered(Request $request)
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
        ]);

    
     
            // Create user account first
            $userData = Session::get('user');
            if (!$userData) {
                return redirect()->back()->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ຜິດພາດ!',
                    'text' => 'ບໍ່ພົບຂໍ້ມູນຜູ້ໃຊ້. ກະລຸນາລົງທະບຽນກ່ອນ.'
                ]);
            }
            $user = User::find($userData['id']);
            if (!$user) {
                return redirect()->back()->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ຜິດພາດ!',
                    'text' => 'ບໍ່ພົບຜູ້ໃຊ້ໃນຖານຂໍ້ມູນ.'
                ]);
            }

            // Find existing student record or create new one
            $student = Student::where('user_id', $user->id)->first();
            if (!$student) {
                $student = new Student();
                $student->user_id = $user->id;
            }

            // Update student fields if provided and not null
            if (!empty($validatedData['name'])) {
                $student->name = $validatedData['name'];
            }
            if (!empty($validatedData['sername'])) {
                $student->sername = $validatedData['sername'];
            }
            if (!empty($validatedData['gender'])) {
                $student->gender = $validatedData['gender'];
            }
            if (!empty($validatedData['birthday'])) {
                $student->birthday = $validatedData['birthday'];
            }
            if (!empty($validatedData['nationality'])) {
                $student->nationality = $validatedData['nationality'];
            }
            if (!empty($validatedData['tell'])) {
                $student->tell = '+85620' . $validatedData['tell'];
            }
            if (!empty($validatedData['address'])) {
                $student->address = $validatedData['address'];
            }

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
            }

            $student->save();

       

            return $student;
       
    }
}
