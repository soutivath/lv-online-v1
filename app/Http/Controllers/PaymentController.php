<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Major;
use App\Models\Employee;
use App\Models\User; // Add this missing import
use App\Models\Registration;
use App\Models\RegistrationDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PDF;
use App\Services\PaymentStatusService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected $paymentStatusService;
    
    public function __construct(PaymentStatusService $paymentStatusService)
    {
        $this->paymentStatusService = $paymentStatusService;
    }

    public function index()
    {
        // Get all payments and their relationships
        $allPayments = Payment::with(['student', 'major.semester', 'major.term', 'major.year', 'employee'])->get();
        
        // Prepare data structures
        $groupedPayments = [];
        $individualPayments = [];
        $handledBillNumbers = [];
        
        // First pass: identify bills with multiple payments and compute their totals
        foreach ($allPayments as $payment) {
            if ($payment->bill_number) {
                // If this bill hasn't been processed yet
                if (!isset($handledBillNumbers[$payment->bill_number])) {
                    // Get all payments with this bill number
                    $paymentsInGroup = $allPayments->where('bill_number', $payment->bill_number);
                    
                    if ($paymentsInGroup->count() > 1) {
                        // Calculate group total
                        $groupTotal = $paymentsInGroup->sum('total_price');
                        
                        // Check payment statuses
                        $allPending = $paymentsInGroup->where('status', 'pending')->count() === $paymentsInGroup->count();
                        $allSuccess = $paymentsInGroup->where('status', 'success')->count() === $paymentsInGroup->count();
                        
                        // Store this bill's data using the first payment as representative
                        $groupedPayments[] = [
                            'payment' => $payment,
                            'count' => $paymentsInGroup->count(),
                            'total' => $groupTotal,
                            'all_pending' => $allPending,
                            'all_success' => $allSuccess,
                            'mixed_status' => !$allPending && !$allSuccess
                        ];
                        
                        // Mark this bill as handled
                        $handledBillNumbers[$payment->bill_number] = true;
                    } else {
                        // Single payment bill, treat as individual payment
                        $individualPayments[] = $payment;
                        $handledBillNumbers[$payment->bill_number] = true;
                    }
                }
            } else {
                // No bill number, so it's individual
                $individualPayments[] = $payment;
            }
        }
        
        return view('Dashboard.payments.index', compact('groupedPayments', 'individualPayments'));
    }

    public function create()
    {
        $students = Student::all();
        $majors = Major::with(['semester', 'term', 'year', 'tuition'])->get();
        return view('Dashboard.payments.create', compact('students', 'majors'));
    }

    // Add a new method to get student's paid majors
    public function getStudentPaidMajors($studentId)
    {
        $student = Student::findOrFail($studentId);
        $paidMajors = Payment::where('student_id', $studentId)
                            ->where('status', 'success')
                            ->pluck('major_id')
                            ->toArray();
        
        return response()->json([
            'paidMajors' => $paidMajors
        ]);
    }

    // Add a new method to get student's related majors
    public function getStudentRelatedMajors($studentId)
    {
        $student = Student::findOrFail($studentId);
        
        // Get majors that this student has registered for
        $registeredMajorIds = $this->paymentStatusService->getRegisteredMajorIdsForStudent($studentId);
        
        $majors = Major::with(['semester', 'term', 'year', 'tuition'])
            ->whereIn('id', $registeredMajorIds)
            ->get();
        
        // Get which majors are already paid
        $paidMajorIds = $this->paymentStatusService->getPaidMajorIdsForStudent($studentId);
        
        return response()->json([
            'majors' => $majors,
            'paidMajorIds' => $paidMajorIds
        ]);
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
            'major_id' => 'required|exists:majors,id',
            'detail_price' => 'required|numeric|min:0',
            'pro' => 'required|numeric|min:0|max:100',
            'payment_proof' => 'nullable|image|max:2048',
        ]);

        // Check if payment for this major already exists for this student
        $existingPayment = Payment::where('student_id', $request->student_id)
                                 ->where('major_id', $request->major_id)
                                 ->where('status', 'success')
                                 ->first();

        if ($existingPayment) {
            return redirect()->back()
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'text' => 'Payment for this major has already been recorded.'
                ]);
        }

        try {
            // Calculate the total price after discount
            $discount = ($request->pro / 100) * $request->detail_price;
            $totalPrice = $request->detail_price - $discount;
            
            // Generate bill number for this payment
            $billNumber = $this->generateBillNumber();
            
            // Create the payment record
            $payment = new Payment();
            $payment->student_id = $request->student_id;
            $payment->major_id = $request->major_id;
            $payment->bill_number = $billNumber; // Add bill number
            $payment->date = $request->date;
            $payment->detail_price = $request->detail_price;
            $payment->pro = $request->pro;
            $payment->total_price = $totalPrice;
            
            // Determine status based on user role
            if (Session::has('user')) {
                $userId = Session::get('user')['id'];
                $user = User::with(['student', 'employee'])->find($userId);
                
                if ($user->employee) {
                    $payment->employee_id = $user->employee->id;
                    $payment->status = 'success'; // Admin-created payments are auto-confirmed
                } else {
                    $payment->status = 'pending'; // Student-created payments need confirmation
                }
            }
            
            // Handle payment proof upload
            if ($request->hasFile('payment_proof')) {
                $payment->payment_proof = $request->file('payment_proof')->store('payment_proofs', 'public');
            }
            
            $payment->save();

            return redirect()->route('payments.index')
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'Success!',
                    'text' => 'Payment created successfully.'
                ]);
        } catch (\Exception $e) {
            return redirect()->route('payments.create')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'text' => 'Failed to create payment. Error: ' . $e->getMessage()
                ]);
        }
    }

    public function show(Payment $payment)
    {
        $payment->load(['student', 'major.semester', 'major.term', 'major.year', 'employee']);
        
        // Check if this payment is part of a group (has bill number)
        $relatedPayments = collect([]);
        $groupTotal = $payment->total_price;
        $isGrouped = false;
        
        if ($payment->bill_number) {
            $relatedPayments = Payment::where('bill_number', $payment->bill_number)
                ->where('id', '!=', $payment->id)
                ->with(['student', 'major.semester', 'major.term', 'major.year'])
                ->get();
                
            if ($relatedPayments->count() > 0) {
                $isGrouped = true;
                $groupTotal = Payment::where('bill_number', $payment->bill_number)->sum('total_price');
            }
        }
        
        return view('Dashboard.payments.show', compact('payment', 'relatedPayments', 'groupTotal', 'isGrouped'));
    }

    public function destroy(Payment $payment)
    {
        try {
            $payment->delete();

            return redirect()->route('payments.index')
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'Success!',
                    'text' => 'Payment deleted successfully.'
                ]);
        } catch (\Exception $e) {
            return redirect()->route('payments.index')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'text' => 'Failed to delete payment. Error: ' . $e->getMessage()
                ]);
        }
    }
    
    public function exportPDF(Payment $payment)
    {
        // Check if this is part of a group bill (has bill number)
        if ($payment->bill_number) {
            // Find all payments with the same bill number
            $groupedPayments = Payment::where('bill_number', $payment->bill_number)
                ->with(['student', 'major.semester', 'major.term', 'major.year', 'employee'])
                ->get();
            
            $data = [
                'payments' => $groupedPayments,
                'student' => $payment->student,
                'employee' => $payment->employee,
                'bill_number' => $payment->bill_number,
                'date' => $payment->date,
                'total' => $groupedPayments->sum('total_price'),
            ];
            
            $pdf = \PDF::loadView('pdfs.grouped-payment-bill', $data);
            return $pdf->download('bill-'.$payment->bill_number.'.pdf');
        }
        
        // Otherwise, handle as a single payment
        $payment->load(['student', 'major.semester', 'major.term', 'major.year', 'employee']);
        
        // Generate QR code content (payment details)
        $qrContent = json_encode([
            'id' => $payment->id,
            'bill_number' => $payment->bill_number,
            'student_id' => $payment->student_id,
            'student_name' => $payment->student->name . ' ' . $payment->student->sername,
            'amount' => $payment->total_price,
            'date' => $payment->date,
            'major' => $payment->major->name,
        ]);
        
        // Generate QR code image
        $qrCode = base64_encode(\QrCode::format('png')
                 ->size(200)
                 ->margin(1)
                 ->generate($qrContent));
        
        $data = [
            'payment' => $payment,
            'qrCode' => $qrCode
        ];
        
        $pdf = \PDF::loadView('pdfs.payment-bill', $data);
        return $pdf->download('payment-bill-'.$payment->id.'.pdf');
    }
    
    public function exportAllPDF()
    {
        $payments = Payment::with(['student', 'major', 'employee'])->get();
        
        $data = [
            'payments' => $payments,
            'date' => now(),
            'total' => $payments->sum('total_price')
        ];
        
        $pdf = \PDF::loadView('pdfs.payments', $data);
        return $pdf->download('all-payments.pdf');
    }

    // Add confirmation endpoint
    public function confirm(Payment $payment)
    {
        try {
            // Check if payment is part of a group
            if ($payment->bill_number) {
                // Confirm all payments with the same bill number
                Payment::where('bill_number', $payment->bill_number)
                      ->update(['status' => 'success']);
                
                $message = 'All payments in this group have been confirmed.';
            } else {
                // Confirm just this payment
                $payment->status = 'success';
                $payment->save();
                
                $message = 'Payment has been confirmed.';
            }
            
            return redirect()->back()
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'Success!',
                    'text' => $message
                ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'text' => 'Failed to confirm payment. Error: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Display the student payment form.
     */
    public function showStudentPaymentForm()
    {
        try {
            // Debug mode - log all steps to identify the issue
            \Log::info('Starting showStudentPaymentForm method');
            
            // Check if user is logged in
            if (!Session::has('user')) {
                \Log::info('User not logged in - redirecting to login');
                return redirect()->route('login')
                    ->with('sweet_alert', [
                        'type' => 'error',
                        'title' => 'ບໍ່ສາມາດເຂົ້າເຖິງ!',
                        'text' => 'ກະລຸນາເຂົ້າສູ່ລະບົບກ່ອນ'
                    ]);
            }
            
            // Get user data
            $userData = Session::get('user');
            \Log::info('User is logged in', ['user_id' => $userData['id']]);
            
            // Load user with student relationship
            $user = User::with('student')->find($userData['id']);
            
            // Check if student profile exists
            if (!$user || !$user->student) {
                \Log::info('User is not a student', ['user_id' => $userData['id']]);
                return redirect()->route('home')
                    ->with('sweet_alert', [
                        'type' => 'error',
                        'title' => 'ບໍ່ພົບຂໍ້ມູນນັກສຶກສາ!',
                        'text' => 'ບັນຊີຂອງທ່ານບໍ່ແມ່ນບັນຊີນັກສຶກສາ'
                    ]);
            }
            
            // Get student information
            $student = $user->student;
            \Log::info('Found student profile', ['student_id' => $student->id]);
            
            // Check for registrations - THIS IS LIKELY THE ISSUE
            try {
                \Log::info('Getting registered major IDs');
                $registeredMajorIds = $this->paymentStatusService->getRegisteredMajorIdsForStudent($student->id);
                \Log::info('Got registered major IDs', ['count' => count($registeredMajorIds)]);
                
                \Log::info('Getting paid major IDs');
                $paidMajorIds = $this->paymentStatusService->getPaidMajorIdsForStudent($student->id);
                \Log::info('Got paid major IDs', ['count' => count($paidMajorIds)]);
                
                // Filter out already paid majors
                $unpaidMajorIds = array_diff($registeredMajorIds, $paidMajorIds);
                \Log::info('Unpaid major IDs', ['count' => count($unpaidMajorIds)]);
            } catch (\Exception $e) {
                \Log::error('Error getting registration data', ['error' => $e->getMessage()]);
                throw $e; // Re-throw to be caught by outer try-catch
            }
            
            // FOR TESTING: If no unpaid majors, show a test major instead of redirecting
            if (empty($unpaidMajorIds)) {
                \Log::info('No unpaid majors found');
                
                // TEMPORARY FIX: For testing, load all majors instead of redirecting
                $majors = Major::with(['semester', 'term', 'year', 'tuition'])->take(5)->get();
                
                if ($majors->isEmpty()) {
                    return redirect()->route('home')
                        ->with('sweet_alert', [
                            'type' => 'info',
                            'title' => 'ບໍ່ມີສາຂາສຳລັບການຊຳລະເງິນ',
                            'text' => 'ບໍ່ມີສາຂາທີ່ສາມາດຊຳລະເງິນໄດ້ໃນຂະນະນີ້'
                        ]);
                }
                
                \Log::info('Showing payment page with all majors instead');
                return view('student-payment', compact('student', 'majors'));
            }
            
            // Get the major details
            $majors = Major::with(['semester', 'term', 'year', 'tuition'])
                ->whereIn('id', $unpaidMajorIds)
                ->get();
            
            \Log::info('Fetched majors', ['count' => $majors->count()]);
            
            if ($majors->isEmpty()) {
                \Log::info('No majors found for unpaid major IDs');
                return redirect()->route('home')
                    ->with('sweet_alert', [
                        'type' => 'info',
                        'title' => 'ບໍ່ມີການລົງທະບຽນຄ້າງຊຳລະ',
                        'text' => 'ທ່ານບໍ່ມີສາຂາທີ່ລົງທະບຽນແລ້ວຍັງບໍ່ໄດ້ຊຳລະເງິນ'
                    ]);
            }
            
            \Log::info('Rendering student-payment view');
            return view('student-payment', compact('student', 'majors'));
        } catch (\Exception $e) {
            \Log::error('Exception in showStudentPaymentForm', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('home')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ເກີດຂໍ້ຜິດພາດ!',
                    'text' => 'ບໍ່ສາມາດໂຫຼດຂໍ້ມູນການຊຳລະເງິນໄດ້: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Process a student payment submission.
     */
    public function storeStudentPayment(Request $request)
    {
        // Log the request for debugging
        \Log::info('Payment submission received', [
            'request_data' => $request->all(),
            'files' => $request->hasFile('payment_proof') ? 'Yes' : 'No'
        ]);
        
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'major_ids' => 'required|string',
                'date' => 'required|date',
                'detail_price' => 'required|numeric|min:0',
                'total_price' => 'required|numeric|min:0',
                'payment_proof' => 'required|image|max:2048',
                'terms_agreement' => 'required|accepted',
                'bill_number' => 'required|string',
            ]);
            
            // Get the currently logged in student
            $userData = Session::get('user');
            $user = User::with('student')->find($userData['id']);
            
            if (!$user || !$user->student) {
                \Log::error('Student not found for user', ['user_id' => $userData['id']]);
                return redirect()->back()->with('error', 'ບໍ່ພົບຂໍ້ມູນນັກສຶກສາ');
            }
          
            // Get major IDs array from comma-separated string
            $majorIds = explode(',', $request->major_ids);
            
            if (empty($majorIds)) {
                return redirect()->back()->with('error', 'ກະລຸນາເລືອກຢ່າງນ້ອຍໜຶ່ງສາຂາ');
            }
            
            // Use the provided bill number for group payments
            $billNumber = $request->bill_number;
            
            // Check if payment for any of these majors already exists for this student
            $existingPayments = Payment::where('student_id', $user->student->id)
                                   ->whereIn('major_id', $majorIds)
                                   ->where('status', 'success')
                                   ->get();
            
            if ($existingPayments->isNotEmpty()) {
                // Use eager loading to get the major details with their relationships
                $existingMajorIds = $existingPayments->pluck('major_id')->toArray();
                $existingMajors = Major::with(['semester', 'term', 'year'])
                    ->whereIn('id', $existingMajorIds)
                    ->get();
                
                // Format each major name with its related data
                $existingMajorNames = $existingMajors
                    ->map(function($major) {
                        return "{$major->name} ({$major->semester->name} {$major->term->name} {$major->year->name})";
                    })
                    ->implode(', ');
               
                return redirect()->back()
                    ->with('sweet_alert', [
                        'type' => 'error',
                        'title' => 'ການຊຳລະເງິນຊ້ຳຊ້ອນ!',
                        'text' => "ທ່ານໄດ້ຊຳລະເງິນສຳລັບສາຂາ {$existingMajorNames} ແລ້ວ"
                    ]);
            }
          
            // Upload the payment proof
            $paymentProofPath = null;
            if ($request->hasFile('payment_proof')) {
                $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
            }
            
            // Track the first payment we create for PDF generation
            $firstPayment = null;
            
            // Create a payment record for each selected major
            foreach ($majorIds as $majorId) {
                $major = Major::with('tuition')->findOrFail($majorId);
                
                $payment = new Payment();
                $payment->student_id = $user->student->id;
                $payment->major_id = $majorId;
                $payment->bill_number = $billNumber;
                $payment->date = $request->date;
                $payment->detail_price = $major->tuition->price;
                $payment->pro = 0; // No discount for individual items in a group
                $payment->total_price = $major->tuition->price;
                $payment->status = 'pending';
                $payment->payment_proof = $paymentProofPath;
                
                $payment->save();
                \Log::info('Payment record created', ['payment_id' => $payment->id]);
                
                // Store first payment ID for PDF redirect
                if (!$firstPayment) {
                    $firstPayment = $payment;
                }
            }
            
            // Flash a success message to session that will be available after redirect
            Session::flash('sweet_alert', [
                'type' => 'success',
                'title' => 'ສຳເລັດ!',
                'text' => 'ການຊຳລະເງິນຂອງທ່ານສຳເລັດແລ້ວ ແລະ ລໍຖ້າການອະນຸມັດ'
            ]);
            
            // Redirect to PDF generation using the bill number
            if ($firstPayment) {
                return redirect()->route('payments.export-pdf', $firstPayment->id);
            }
            
            // Fallback if for some reason no payments were created
            return redirect()->route('main')
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'ສຳເລັດ!',
                    'text' => 'ການຊຳລະເງິນຂອງທ່ານສຳເລັດແລ້ວ ແລະ ລໍຖ້າການອະນຸມັດ'
                ]);
                
        } catch (\Exception $e) {
            \Log::error('Error in payment submission', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ເກີດຂໍ້ຜິດພາດ!',
                    'text' => 'ການຊຳລະເງິນບໍ່ສຳເລັດ: ' . $e->getMessage()
                ])
                ->withInput();
        }
    }
}
