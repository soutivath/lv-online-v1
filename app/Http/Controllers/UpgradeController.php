<?php

namespace App\Http\Controllers;

use App\Models\Upgrade;
use App\Models\UpgradeDetail;
use App\Models\Student;
use App\Models\Major;
use App\Models\Subject;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;

class UpgradeController extends Controller
{
    public function index()
    {
        $upgrades = Upgrade::with(['student', 'major', 'employee', 'upgradeDetails'])->get();
        return view('Dashboard.upgrades.index', compact('upgrades'));
    }

    public function create()
    {
        $students = Student::all();
        $majors = Major::with(['semester', 'term', 'year'])->get();
        $subjects = Subject::with('credit')->get();
        return view('Dashboard.upgrades.create', compact('students', 'majors', 'subjects'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'major_id' => 'required|exists:majors,id',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
            'payment_proof' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $upgrade = new Upgrade();
            $upgrade->student_id = $request->student_id;
            $upgrade->date = $request->date;
            $upgrade->major_id = $request->major_id;
            
            // Get employee_id from session if user is logged in as employee
            if (Session::has('user')) {
                $userId = Session::get('user')['id'];
                $user = User::with(['student', 'employee'])->find($userId);
                
                if ($user && $user->employee) {
                    $upgrade->employee_id = $user->employee->id;
                    $upgrade->payment_status = 'success'; // Admin-created upgrades are auto-confirmed
                } else {
                    $upgrade->payment_status = 'pending'; // Student-created upgrades need confirmation
                }
            }
            
            // Handle payment proof upload
            if ($request->hasFile('payment_proof')) {
                $upgrade->payment_proof = $request->file('payment_proof')->store('upgrade_proofs', 'public');
            }
            
            $upgrade->save();

            $totalAmount = 0;

            foreach ($request->subjects as $subjectId) {
                $subject = Subject::with('credit')->findOrFail($subjectId);
                $price = $subject->credit->price;

                $upgradeDetail = new UpgradeDetail();
                $upgradeDetail->upgrade_id = $upgrade->id;
                $upgradeDetail->subject_id = $subjectId;
                $upgradeDetail->detail_price = $price;
                $upgradeDetail->total_price = $price; // No discount for individual subjects
                $upgradeDetail->save();

                $totalAmount += $price;
            }

            DB::commit();

            return redirect()->route('upgrades.index')
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'Success!',
                    'text' => 'Upgrade created successfully.'
                ]);
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->route('upgrades.create')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'text' => 'Failed to create upgrade. Error: ' . $e->getMessage()
                ]);
        }
    }

    public function show(Upgrade $upgrade)
    {
        $upgrade->load(['student', 'major.semester', 'major.term', 'major.year', 'employee', 'upgradeDetails.subject.credit']);
        return view('Dashboard.upgrades.show', compact('upgrade'));
    }

    public function destroy(Upgrade $upgrade)
    {
        DB::beginTransaction();
        try {
            // Delete related upgrade details
            $upgrade->upgradeDetails()->delete();
            
            // Delete the upgrade
            $upgrade->delete();

            DB::commit();

            return redirect()->route('upgrades.index')
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'Success!',
                    'text' => 'Upgrade deleted successfully.'
                ]);
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->route('upgrades.index')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'text' => 'Failed to delete upgrade. Error: ' . $e->getMessage()
                ]);
        }
    }

    public function exportPDF(Upgrade $upgrade)
    {
        $upgrade->load(['student', 'major.semester', 'major.term', 'major.year', 
                       'employee', 'upgradeDetails.subject.credit']);
        
        $data = [
            'upgrade' => $upgrade,
            'date' => now(),
            'totalAmount' => $upgrade->upgradeDetails->sum('total_price')
        ];
        
        $pdf = \PDF::loadView('pdfs.upgrade', $data);
        return $pdf->download('upgrade-'.$upgrade->id.'.pdf');
    }
    
    public function exportAllPDF()
    {
        $upgrades = Upgrade::with(['student', 'major', 'employee', 'upgradeDetails'])->get();
        
        $data = [
            'upgrades' => $upgrades,
            'date' => now()
        ];
        
        $pdf = \PDF::loadView('pdfs.upgrades', $data);
        return $pdf->download('all-upgrades.pdf');
    }

    // Add method to confirm upgrade payment
    public function confirmPayment(Upgrade $upgrade)
    {
        try {
            $upgrade->payment_status = 'success';
            $upgrade->save();
            
            return redirect()->route('upgrades.show', $upgrade->id)
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'Success!',
                    'text' => 'Upgrade payment has been confirmed.'
                ]);
        } catch (\Exception $e) {
            return redirect()->route('upgrades.show', $upgrade->id)
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'text' => 'Failed to confirm payment. Error: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Get subjects available for a specific major
     */
    public function getSubjectsByMajor($majorId)
    {
        try {
            \Log::info('Fetching subjects for major ID: ' . $majorId);
            
            // Using major_id instead of department_id
            $subjects = Subject::with('credit')
                // ->where('major_id', $majorId)
                ->get();
            
            \Log::info('Found subjects: ' . $subjects->count());
            
            return response()->json($subjects);
        } catch (\Exception $e) {
            \Log::error('Error fetching subjects: ' . $e->getMessage());
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the student upgrade form
     */
    public function showStudentUpgrade()
    {
        try {
            // Get majors for the dropdown
            $majors = Major::with(['semester', 'term', 'year'])->get();
            
            return view('student-upgrade', compact('majors'));
        } catch (\Exception $e) {
            return redirect()->route('home')->with('sweet_alert', [
                'type' => 'error',
                'title' => 'ເກີດຂໍ້ຜິດພາດ!',
                'text' => 'ບໍ່ສາມາດໂຫຼດຂໍ້ມູນສາຂາຮຽນໄດ້: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Store a student upgrade from the public form
     */
    public function storeStudentUpgrade(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'major_id' => 'required|exists:majors,id',
            'date' => 'required|date',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
            'payment_proof' => 'required|image|max:2048',
            'terms_agreement' => 'required|accepted',
        ]);
        
        DB::beginTransaction();
        try {
            // Get the currently logged in student via session
            $userId = session('auth_user.id') ?? null;
            
            if (!$userId) {
                return redirect()->back()->with('error', 'ທ່ານຕ້ອງເຂົ້າສູ່ລະບົບກ່ອນ');
            }
            
            $user = User::with('student')->find($userId);
            
            if (!$user || !$user->student) {
                return redirect()->back()->with('error', 'ບໍ່ພົບຂໍ້ມູນນັກສຶກສາ');
            }
            
            // Create new upgrade record
            $upgrade = new Upgrade();
            $upgrade->student_id = $user->student->id;
            $upgrade->date = $request->date;
            $upgrade->major_id = $request->major_id;
            $upgrade->payment_status = 'pending';
            
            // Handle payment proof upload
            if ($request->hasFile('payment_proof')) {
                $upgrade->payment_proof = $request->file('payment_proof')->store('upgrade_proofs', 'public');
            }
            
            $upgrade->save();
            
            // Add upgrade details for each selected subject
            $totalAmount = 0;
            
            foreach ($request->subjects as $subjectId) {
                $subject = Subject::with('credit')->findOrFail($subjectId);
                $price = $subject->credit->price;
                
                $upgradeDetail = new UpgradeDetail();
                $upgradeDetail->upgrade_id = $upgrade->id;
                $upgradeDetail->subject_id = $subjectId;
                $upgradeDetail->detail_price = $price;
                $upgradeDetail->total_price = $price;
                $upgradeDetail->save();
                
                $totalAmount += $price;
            }
            
            DB::commit();
            
            return redirect()->route('home')->with('sweet_alert', [
                'type' => 'success',
                'title' => 'ສຳເລັດ!',
                'text' => 'ການລົງທະບຽນວິຊາເສີມຂອງທ່ານສຳເລັດແລ້ວ ແລະ ລໍຖ້າການອະນຸມັດ'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()->with('sweet_alert', [
                'type' => 'error',
                'title' => 'ເກີດຂໍ້ຜິດພາດ!',
                'text' => 'ການລົງທະບຽນບໍ່ສຳເລັດ: ' . $e->getMessage()
            ])->withInput();
        }
    }
}
