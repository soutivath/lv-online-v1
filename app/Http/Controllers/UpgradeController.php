<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
    public function index(Request $request)
    {
        // Get the selected major name from the request
        $majorName = $request->input('major_name');
        
        // Get all available majors and group them by name
        $majors = Major::select('id', 'name')
            ->orderBy('name')
            ->get()
            ->groupBy('name')
            ->map(function($group) {
                // For each group, take the first major's ID as the representative
                $firstMajor = $group->first();
                return [
                    'id' => $firstMajor->id,
                    'name' => $firstMajor->name,
                    'count' => $group->count()
                ];
            })
            ->values();
            
        // Base query for upgrades with eager loading
        $upgradesQuery = Upgrade::with(['student', 'major', 'employee', 'upgradeDetails']);
        
        // Apply major filter if provided
        if ($majorName) {
            // Find all majors with the selected name
            $majorIds = Major::where('name', $majorName)->pluck('id')->toArray();
            
            // Filter upgrades by those major IDs
            $upgradesQuery->whereIn('major_id', $majorIds);
        }
        
        // Get upgrades
        $upgrades = $upgradesQuery->get();
        
        return view('Dashboard.upgrades.index', compact('upgrades', 'majors', 'majorName'));
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
                    'title' => 'ສຳເລັດ!',
                    'text' => 'ສ້າງການອັບເກຣດສຳເລັດແລ້ວ.'
                ]);
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->route('upgrades.create')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ຜິດພາດ!',
                    'text' => 'ການສ້າງການອັບເກຣດລົ້ມເຫລວ. ຂໍ້ຜິດພາດ: ' . $e->getMessage()
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
                    'title' => 'ສຳເລັດ!',
                    'text' => 'ລຶບການອັບເກຣດສຳເລັດແລ້ວ.'
                ]);
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->route('upgrades.index')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ຜິດພາດ!',
                    'text' => 'ການລຶບການອັບເກຣດລົ້ມເຫລວ. ຂໍ້ຜິດພາດ: ' . $e->getMessage()
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
                    'title' => 'ສຳເລັດ!',
                    'text' => 'ການຢືນຢັນການຊຳລະເງິນອັບເກຣດສຳເລັດແລ້ວ.'
                ]);
        } catch (\Exception $e) {
            return redirect()->route('upgrades.show', $upgrade->id)
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ຜິດພາດ!',
                    'text' => 'ການຢືນຢັນການຊຳລະເງິນລົ້ມເຫລວ. ຂໍ້ຜິດພາດ: ' . $e->getMessage()
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
            $major = Major::with(['subjects.credit'])->findOrFail($majorId);
            $subjects = $major->subjects;

            //all subject
            $allSubjects = Subject::with('credit')->get();
        
            
            \Log::info('Found subjects: ' . $subjects->count());
            
            return response()->json($allSubjects);
            // return response()->json($subjects);
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
            
            // Get all students for the student dropdown
            $students = Student::all();
            
            return view('student-upgrade', compact('majors', 'students'));
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
            'student_id' => 'required|exists:students,id', // Add this validation rule
            'major_id' => 'required|exists:majors,id',
            'date' => 'required|date',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
            'payment_proof' => 'required|image|max:2048',
            'terms_agreement' => 'required|accepted',
        ]);
        
        DB::beginTransaction();
        try {
            // Get the student from request instead of session
            $student = Student::findOrFail($request->student_id);
            
            // Create new upgrade record
            $upgrade = new Upgrade();
            $upgrade->student_id = $student->id;
            $upgrade->date = Carbon::parse($request->date)->format('Y-m-d H:i:s');
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
            
            // Instead of redirecting to PDF, return to the student upgrade page with success message
            return redirect()->route('student.upgrade')
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'ສຳເລັດ!',
                    'text' => 'ການອັບເກຣດວິຊາຮຽນສຳເລັດແລ້ວ ແລະ ລໍຖ້າການອະນຸມັດ'
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
