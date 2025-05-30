<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Registration;
use App\Models\RegistrationDetail;
use App\Models\Student;
use App\Models\Major;
use App\Models\Year;
use App\Models\Term;
use App\Models\Semester;
use App\Models\Employee;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;
use App\Services\PaymentStatusService;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    protected $paymentStatusService;

    public function __construct(PaymentStatusService $paymentStatusService)
    {
        $this->paymentStatusService = $paymentStatusService;
    }

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
            
        // Base query for registrations with eager loading
        $registrationsQuery = Registration::with(['student', 'registrationDetails.major']);
        
        // Apply major filter if provided
        if ($majorName) {
            // Find all majors with the selected name
            $majorIds = Major::where('name', $majorName)->pluck('id')->toArray();
            
            // Find registrations that have any registration detail with a major ID in our list
            $registrationsQuery->whereHas('registrationDetails', function($query) use ($majorIds) {
                $query->whereIn('major_id', $majorIds);
            });
        }
        
        // Get the registrations
        $registrations = $registrationsQuery->get();
        
        return view('Dashboard.registrations.index', compact('registrations', 'majors', 'majorName'));
    }

    public function create()
    {
        $students = Student::all();
        $majors = Major::with(['semester', 'term', 'year', 'tuition'])->get();
        $years = Year::all();
        $terms = Term::all();
        $semesters = Semester::all();
        return view('Dashboard.registrations.create', compact(
            'students',
            'majors',
            'years',
            'terms',
            'semesters'
        ));
    }

    /**
     * Generate a unique bill number
     */
    private function generateBillNumber()
    {
        $prefix = 'BIL';
        $year = date('Y');
        $month = date('m');
        $random = strtoupper(Str::random(4));
        $timestamp = time();

        return "{$prefix}-{$year}{$month}-{$random}-{$timestamp}";
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'pro' => 'required|numeric|min:0|max:100',
            'major_ids' => 'required|string',
            'payment_proof' => 'nullable|image|max:2048',
        ]);

        // Convert major_ids string to array
        $majorIds = explode(',', $request->major_ids);

        // Check if there are any major IDs
        if (empty($majorIds)) {
            return redirect()->route('registrations.create')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ຜິດພາດ!',
                    'text' => 'ກະລຸນາເລືອກຢ່າງນ້ອຍໜຶ່ງສາຂາສຳລັບການລົງທະບຽນ.'
                ]);
        }

        // Check for duplicate majors
        if (count($majorIds) !== count(array_unique($majorIds))) {
            return redirect()->route('registrations.create')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ຜິດພາດ!',
                    'text' => 'ພົບສາຂາຊ້ຳກັນ. ກະລຸນາເລືອກແຕ່ລະສາຂາພຽງຄັ້ງດຽວ.'
                ]);
        }

        // Check if student has ever registered for any of these majors before (first-year check)
        $existingRegistrations = RegistrationDetail::whereHas('registration', function($query) use ($request) {
            $query->where('student_id', $request->student_id);
        })->whereIn('major_id', $majorIds)->get();

        if ($existingRegistrations->isNotEmpty()) {
            // Get the names of already registered majors for better error message
            $existingMajorIds = $existingRegistrations->pluck('major_id')->toArray();
            $existingMajors = Major::with(['semester', 'term', 'year'])
                ->whereIn('id', $existingMajorIds)
                ->get();
            
            // Format the major names nicely
            $majorNames = $existingMajors->map(function($major) {
                return "{$major->name} ({$major->semester->name} {$major->term->name} {$major->year->name})";
            })->implode(', ');
            
            return redirect()->route('registrations.create')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ບໍ່ສາມາດລົງທະບຽນໄດ້',
                    'text' => "ນັກສຶກສາໄດ້ລົງທະບຽນສາຂາ {$majorNames} ແລ້ວ. ການລົງທະບຽນນີ້ສະຫງວນໄວ້ສຳລັບນັກສຶກສາປີທຳອິດເທົ່ານັ້ນ."
                ])
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $registration = new Registration();
            $registration->student_id = $request->student_id;
            $registration->date = $request->date;
            $registration->pro = $request->pro;

            // Get employee_id from session if user is logged in as employee
            $employeeId = null;
            $paymentStatus = 'pending'; // Default for student registrations

            if (Session::has('user')) {
                $userId = Session::get('user')['id'];
                $user = User::with(['student', 'employee'])->find($userId);

                if ($user && $user->employee) {
                    $employeeId = $user->employee->id;
                    $registration->employee_id = $employeeId;
                    $paymentStatus = 'success'; // Admin-created registrations are auto-confirmed
                }
            }

            $registration->payment_status = $paymentStatus;

            // Handle payment proof upload
            $paymentProofPath = null;
            if ($request->hasFile('payment_proof')) {
                $paymentProofPath = $request->file('payment_proof')->store('registration_proofs', 'public');
                $registration->payment_proof = $paymentProofPath;
            }

            $registration->save();

            $totalRegistrationPrice = 0;

            // Generate a single bill number for all payments from this registration
            $billNumber = $this->generateBillNumber();

            // Create registration details for each selected major
            foreach ($majorIds as $majorId) {
                $major = Major::with("tuition")->findOrFail($majorId);

                $detail_price = $major->tuition->price;

                $registrationDetail = new RegistrationDetail();
                $registrationDetail->registration_id = $registration->id;
                $registrationDetail->major_id = $majorId;
                $registrationDetail->detail_price = $detail_price;

                // Calculate discounted price
                $discount = ($request->pro / 100) * $detail_price;
                $registrationDetail->total_price = $detail_price - $discount;

                $registrationDetail->save();

                // Create corresponding payment record with the bill number
                Payment::create([
                    'student_id' => $request->student_id,
                    'major_id' => $majorId,
                    'employee_id' => $employeeId,
                    'bill_number' => $billNumber, // Add bill number to link payments
                    'date' => $request->date,
                    'detail_price' => $detail_price,
                    'pro' => $request->pro,
                    'total_price' => $detail_price - $discount,
                    'status' => $paymentStatus,
                    'payment_proof' => $paymentProofPath
                ]);

                $totalRegistrationPrice += $registrationDetail->total_price;
            }

            DB::commit();

            return redirect()->route('registrations.show', $registration->id)
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'ສຳເລັດ!',
                    'text' => 'ສ້າງການລົງທະບຽນສຳເລັດແລ້ວກັບ ' . count($majorIds) . ' ສາຂາ.'
                ]);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('registrations.create')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ຜິດພາດ!',
                    'text' => 'ການສ້າງການລົງທະບຽນລົ້ມເຫລວ. ຂໍ້ຜິດພາດ: ' . $e->getMessage()
                ]);
        }
    }

    public function show(Registration $registration)
    {
        $registration->load(['student', 'employee', 'registrationDetails.major.semester', 'registrationDetails.major.term', 'registrationDetails.major.year']);

        // For each major in the registration, check if it's been paid via a direct payment
        $majorPaymentStatuses = [];
        foreach ($registration->registrationDetails as $detail) {
            $payment = Payment::where('student_id', $registration->student_id)
                ->where('major_id', $detail->major_id)
                ->where('status', 'success')
                ->first();

            $majorPaymentStatuses[$detail->major_id] = [
                'paid_directly' => $payment ? true : false,
                'payment_id' => $payment ? $payment->id : null
            ];
        }

        return view(
            'Dashboard.registrations.show',
            compact('registration', 'majorPaymentStatuses')
        );
    }

    public function destroy(Registration $registration)
    {
        DB::beginTransaction();
        try {
            // Get majors from this registration for cleanup
            $majorIds = $registration->registrationDetails()->pluck('major_id')->toArray();

            // Delete related registration details
            $registration->registrationDetails()->delete();

            // Delete the registration
            $registration->delete();

            // Only delete automatic payments that were created with the registration 
            // and still have the same status (avoid deleting payments that were updated separately)
            foreach ($majorIds as $majorId) {
                $payment = Payment::where('student_id', $registration->student_id)
                    ->where('major_id', $majorId)
                    ->where('status', $registration->payment_status)
                    ->first();

                if ($payment) {
                    $payment->delete();
                }
            }

            DB::commit();

            return redirect()->route('registrations.index')
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'ສຳເລັດ!',
                    'text' => 'ລຶບການລົງທະບຽນສຳເລັດແລ້ວ.'
                ]);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('registrations.index')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ຜິດພາດ!',
                    'text' => 'ການລຶບການລົງທະບຽນລົ້ມເຫລວ. ຂໍ້ຜິດພາດ: ' . $e->getMessage()
                ]);
        }
    }

    public function exportPDF(Registration $registration)
    {
        $registration->load([
            'student',
            'employee',
            'registrationDetails.major.semester',
            'registrationDetails.major.term',
            'registrationDetails.major.year'
        ]);

        $data = [
            'registration' => $registration,
            'date' => now()
        ];

        $pdf = \PDF::loadView('pdfs.registration', $data);
        return $pdf->download('registration-' . $registration->id . '.pdf');
    }

    public function exportAllPDF()
    {
        $registrations = Registration::with(['student', 'employee', 'registrationDetails.major'])->get();

        $data = [
            'registrations' => $registrations,
            'date' => now()
        ];

        $pdf = \PDF::loadView('pdfs.registrations', $data);
        return $pdf->download('all-registrations.pdf');
    }

    // Add method to confirm registration payment
    public function confirmPayment(Registration $registration)
    {
        try {
            $registration->payment_status = 'success';
            $registration->save();

            // Generate a single bill number for all payments
            $billNumber = $this->generateBillNumber();

            // Update existing payment records for each major in this registration
            foreach ($registration->registrationDetails as $detail) {
                // Find existing payment or create new one
                $payment = Payment::firstOrCreate(
                    [
                        'student_id' => $registration->student_id,
                        'major_id' => $detail->major_id
                    ],
                    [
                        'employee_id' => $registration->employee_id,
                        'bill_number' => $billNumber, // Add bill number
                        'date' => now(),
                        'detail_price' => $detail->detail_price,
                        'pro' => $registration->pro,
                        'total_price' => $detail->total_price,
                        'payment_proof' => $registration->payment_proof
                    ]
                );

                // Update payment status to success and ensure bill number is set
                if ($payment->status !== 'success' || empty($payment->bill_number)) {
                    $payment->status = 'success';
                    $payment->bill_number = $billNumber;
                    $payment->save();
                }
            }

            return redirect()->route('registrations.show', $registration->id)
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'ສຳເລັດ!',
                    'text' => 'ຢືນຢັນການຊຳລະເງິນສຳເລັດແລ້ວ ແລະ ອັບເດດບັນທຶກການຊຳລະເງິນແລ້ວ.'
                ]);
        } catch (\Exception $e) {
            return redirect()->route('registrations.show', $registration->id)
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ຜິດພາດ!',
                    'text' => 'ການຢືນຢັນການຊຳລະເງິນລົ້ມເຫລວ. ຂໍ້ຜິດພາດ: ' . $e->getMessage()
                ]);
        }
    }



    public function studentRegistration(Request $request)
    {


        $validatedData = $request->validate([
            // 'pro' => 'required|numeric|min:0|max:100',
            'major_ids' => 'required|string',
            'payment_proof' => 'nullable|image|max:2048',
        ]);



        DB::beginTransaction();
        try {
            $request->merge([
                'pro'=>0
            ]);
            $stdController = new StudentController();
            
            $stdData = $stdController->updatedRegistered($request);
          

            $registration = new Registration();
            $registration->student_id = $stdData->id;
            $registration->date = Carbon::parse($request->date)->format('Y-m-d H:i:s');
            // $registration->pro = $request->pro;
            $registration->pro = 0;

            // Get employee_id from session if user is logged in as employee
            $employeeId = null;
            $paymentStatus = 'pending'; // Default for student registrations


            $registration->payment_status = $paymentStatus;

            // Handle payment proof upload
            $paymentProofPath = null;
            if ($request->hasFile('payment_proof')) {
                $paymentProofPath = $request->file('payment_proof')->store('registration_proofs', 'public');
                $registration->payment_proof = $paymentProofPath;
            }

            $registration->save();

            $totalRegistrationPrice = 0;

            // Generate a single bill number for all payments from this registration
            $billNumber = $this->generateBillNumber();

            // Convert major_ids string to array
            $majorIds = array_filter(explode(',', $request->major_ids));

            // $majorIds = explode(',', $request->major_ids);
            
            if (empty($majorIds)) {
                return redirect()->back()
                    ->with('sweet_alert', [
                        'type' => 'error',
                        'title' => 'ກະລຸນາເລືອກສາຂາ',
                        'text' => 'ກະລຸນາເລືອກຢ່າງນ້ອຍໜຶ່ງສາຂາສຳລັບການລົງທະບຽນ'
                    ]);
            }
            
            // Check if student already has registrations for any of these majors
            $existingRegistrations = RegistrationDetail::whereHas('registration', function($query) use ($stdData) {
                $query->where('student_id', $stdData->id);
            })->whereIn('major_id', $majorIds)->get();
            
            // if ($existingRegistrations->isNotEmpty()) {
            //     // Get the names of already registered majors for better error message
            //     $existingMajorIds = $existingRegistrations->pluck('major_id')->toArray();
            //     $existingMajors = Major::with(['semester', 'term', 'year'])
            //         ->whereIn('id', $existingMajorIds)
            //         ->get();
                
            //     // Format the major names nicely
            //     $majorNames = $existingMajors->map(function($major) {
            //         return "{$major->name} ({$major->semester->name} {$major->term->name} {$major->year->name})";
            //     })->implode(', ');
              
            //     return redirect()->back()
            //         ->with('sweet_alert', [
            //             'type' => 'error',
            //             'title' => 'ທ່ານໄດ້ລົງທະບຽນແລ້ວ',
            //             'text' => "ທ່ານໄດ້ລົງທະບຽນສາຂາ {$majorNames} ແລ້ວ. ຟັງຊັນນີ້ສະຫງວນໄວ້ສຳລັບນັກສຶກສາປີທຳອິດເທົ່ານັ້ນ."
            //         ])
            //         ->withInput();
            // }

            //for rollback
            // Get registrations with their details and majors
            // $existingRegistrations = Registration::where('student_id', $stdData->id)
            //     ->with('registrationDetails.major')
            //     ->get();
            
            // // Build the array of used majors manually
            // $usedToRegistredMajor = [];
            // foreach ($existingRegistrations as $registration) {
            //     foreach ($registration->registrationDetails as $detail) {
            //         if ($detail->major) {
            //             $usedToRegistredMajor[$detail->major->semester_id] = $detail->major->name;
            //         }
            //     }
            // }
            
            // $selectedMajors = Major::whereIn('id', $majorIds)->get();
            // foreach($selectedMajors as $selectedMajor){
            //     $nameOfMajorInsemester = $usedToRegistredMajor[$selectedMajor->semester_id] ?? null;
            //     if($nameOfMajorInsemester!= null && ($nameOfMajorInsemester != $selectedMajor->name)){
            //         $semesterName = Semester::find($selectedMajor->semester_id)->name;
            //         return redirect()->back()
            //         ->with('sweet_alert', [
            //             'type' => 'error',
            //             'title' => 'ບໍ່ສາມາດລົງທະບຽນໄດ້',
            //             'text' => "ນັກສຶກສາໄດ້ລົງທະບຽນໃນ {$semesterName} ສາຂາ {$nameOfMajorInsemester} ແລ້ວ."
            //         ])
            //         ->withInput();
            //     }
            // }

            //for rollback


            // Create registration details for each selected major
            foreach ($majorIds as $majorId) {
                $major = Major::with('tuition')->findOrFail($majorId);

                $detail_price = $major->tuition->price;

                $registrationDetail = new RegistrationDetail();
                $registrationDetail->registration_id = $registration->id;
                $registrationDetail->major_id = $majorId;
                $registrationDetail->detail_price = $detail_price;

                // Calculate discounted price
                $discount = ($request->pro / 100) * $detail_price;
                $registrationDetail->total_price = $detail_price - $discount;

                $registrationDetail->save();

                // Create corresponding payment record with the bill number
                // Payment::create([
                //     'student_id' => $stdData->id,
                //     'major_id' => $majorId,
                //     'employee_id' => $employeeId,
                //     'bill_number' => $billNumber, // Add bill number to link payments
                //     'date' => Carbon::parse($request->date)->format('Y-m-d H:i:s'),
                //     'detail_price' => $detail_price,
                //     'pro' => $request->pro,
                //     'total_price' => $detail_price - $discount,
                //     'status' => $paymentStatus,
                //     'payment_proof' => $paymentProofPath
                // ]);

                // $totalRegistrationPrice += $registrationDetail->total_price;
            }

            DB::commit();
            Session::flash('registration_completed', true);
            Session::flash('registration_id', $registration->id);
            
            // return redirect()->route('registrations.export-pdf', $registration->id)
            //     ->with('sweet_alert', [
            //         'type' => 'success',
            //         'title' => 'Success!',
            //         'text' => 'Registration created successfully.'
            //     ]);
            // return redirect()->route('registrations.export-pdf', $registration->id);

            return redirect()->back()
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'ສຳເລັດ!',
                    'text' => 'ສ້າງການລົງທະບຽນສຳເລັດແລ້ວກັບ ' . count($majorIds) . ' ສາຂາ.'
                ]);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ຜິດພາດ!',
                    'text' => 'ການສ້າງການລົງທະບຽນລົ້ມເຫລວ. ຂໍ້ຜິດພາດ: ' . $e->getMessage()
                ]);
        }
    }
}
