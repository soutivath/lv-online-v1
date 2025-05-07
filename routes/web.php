<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\YearController;
use App\Http\Controllers\TuitionController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UpgradeController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home route - always redirect to main page
Route::get('/', function () {
    return view('main-page');
})->name('home');

// Student Registration Routes
Route::get('/student-registration', [App\Http\Controllers\StudentController::class, 'showRegistrationForm'])->name('student.registration');
// Route::post('/student-register', 'App\Http\Controllers\StudentController@register')->name('student.register');

// Student Payment Route
Route::get('/student-payment', 'App\Http\Controllers\PaymentController@showStudentPaymentForm')->name('student.payment');
Route::post('/student-payment-submit', 'App\Http\Controllers\PaymentController@storeStudentPayment')->name('student.payment.submit');

// Student Upgrade Routes
Route::get('/student-upgrade', 'App\Http\Controllers\UpgradeController@showStudentUpgrade')->name('student.upgrade');
Route::post('/student-upgrade-submit', 'App\Http\Controllers\UpgradeController@storeStudentUpgrade')->name('student.upgrade.submit');

// Make sure the API route is outside middleware groups and accessible to all
Route::get('/api/subjects-by-major/{major}', 'App\Http\Controllers\UpgradeController@getSubjectsByMajor');

// API routes for dropdown dependencies
Route::get('/api/academic-years', 'App\Http\Controllers\YearController@getYears');
Route::get('/api/terms-by-year/{yearId}', 'App\Http\Controllers\TermController@getTermsByYear');
Route::get('/api/semesters-by-year-term/{yearId}/{termId}', 'App\Http\Controllers\SemesterController@getSemestersByYearTerm');
Route::get('/api/majors-by-year-term-semester/{yearId}/{termId}/{semesterId}', 'App\Http\Controllers\MajorController@getMajorsByYearTermSemester');

// API routes for dropdown dependencies - make sure they are accessible without authentication
Route::get('/api/academic-years', [App\Http\Controllers\YearController::class, 'getYears']);
Route::get('/api/terms-by-year/{yearId}', [App\Http\Controllers\TermController::class, 'getTermsByYear']);
Route::get('/api/semesters-by-year-term/{yearId}/{termId}', [App\Http\Controllers\SemesterController::class, 'getSemestersByYearTerm']);
Route::get('/api/majors-by-year-term-semester/{yearId}/{termId}/{semesterId}', [App\Http\Controllers\MajorController::class, 'getMajorsByYearTermSemester']);

// Auth routes (custom)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', 'App\Http\Controllers\AuthController@logout')->name('logout');

// Student main page route
Route::get('/main', [MainController::class, 'index'])->name('main');

// Protected admin routes that require authentication
Route::middleware(['check.role:admin,student'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Students
    Route::resource('students', StudentController::class);
    Route::get('students/{student}/export-pdf', [StudentController::class, 'exportPDF'])->name('students.export-pdf');
    Route::get('students-export-all', [StudentController::class, 'exportAllPDF'])->name('students.export-all-pdf');

    // Employees
    Route::resource('employees', EmployeeController::class);
    Route::get('employees/{employee}/export-pdf', [EmployeeController::class, 'exportPDF'])->name('employees.export-pdf');
    Route::get('employees-export-all', [EmployeeController::class, 'exportAllPDF'])->name('employees.export-all-pdf');


    Route::get('semesters/export-pdf', [SemesterController::class, 'exportPDF'])->name('semesters.export-pdf');
    // Semesters
    Route::resource('semesters', SemesterController::class);

    // Terms
    Route::get('terms/export-pdf', [TermController::class, 'exportPDF'])->name('terms.export-pdf');
    Route::resource('terms', TermController::class);
  

    // Years
    Route::get('years/export-pdf', [YearController::class, 'exportPDF'])->name('years.export-pdf');
    Route::resource('years', YearController::class);

    // Tuitions
    Route::get('tuitions/export-pdf', [TuitionController::class, 'exportPDF'])->name('tuitions.export-pdf');

    Route::resource('tuitions', TuitionController::class);

    // Credits
    Route::get('credits/export-pdf', [CreditController::class, 'exportPDF'])->name('credits.export-pdf');

    Route::resource('credits', CreditController::class);

    // Subjects
    Route::get('subjects/export-all-pdf', [SubjectController::class, 'exportAllPDF'])->name('subjects.export-all-pdf');
    Route::get('subjects/{subject}/export-pdf', [SubjectController::class, 'exportPDF'])->name('subjects.export-pdf');
    Route::get('subjects/export-pdf', [SubjectController::class, 'exportPDF'])->name('subjects.export-pdf-all'); // Renamed to avoid conflict
    Route::resource('subjects', SubjectController::class);

    // Majors
    Route::get('majors/filtered', [MajorController::class, 'getFilteredMajors'])->name('majors.filtered');
    // Important: Place the export-all-pdf route BEFORE the parameterized route
    Route::get('majors/export-all-pdf', [MajorController::class, 'exportAllPDF'])->name('majors.export-all-pdf');
    Route::get('majors/{major}/export-pdf', [MajorController::class, 'exportPDF'])->name('majors.export-pdf');
    Route::resource('majors', MajorController::class);

    // Registrations
    Route::resource('registrations', RegistrationController::class);
    Route::patch('registrations/{registration}/confirm-payment', [RegistrationController::class, 'confirmPayment'])->name('registrations.confirm-payment');
    Route::get('registrations/{registration}/export-pdf', [RegistrationController::class, 'exportPDF'])->name('registrations.export-pdf');
    Route::get('registrations-export-all', [RegistrationController::class, 'exportAllPDF'])->name('registrations.export-all-pdf');
    Route::post('/student-register', [RegistrationController::class, 'studentRegistration'])
    // ->name('registrations.student')
    ->name('student.register');

    // Payments
    Route::resource('payments', PaymentController::class);
    Route::get('student-paid-majors/{student}', [PaymentController::class, 'getStudentPaidMajors'])->name('student.paid-majors');
    Route::get('student-related-majors/{student}', [PaymentController::class, 'getStudentRelatedMajors'])->name('student.related-majors');
    Route::patch('payments/{payment}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
    Route::get('payments/{payment}/export-pdf', [PaymentController::class, 'exportPDF'])->name('payments.export-pdf');
    Route::get('payments-export-all', [PaymentController::class, 'exportAllPDF'])->name('payments.export-all-pdf');

    // Upgrades
    Route::resource('upgrades', UpgradeController::class);
    Route::patch('upgrades/{upgrade}/confirm-payment', [UpgradeController::class, 'confirmPayment'])->name('upgrades.confirm-payment');
    Route::get('upgrades/{upgrade}/export-pdf', [UpgradeController::class, 'exportPDF'])->name('upgrades.export-pdf');
    Route::get('upgrades-export-all', [UpgradeController::class, 'exportAllPDF'])->name('upgrades.export-all-pdf');
});

// Make sure the PDF export route is accessible without authentication
Route::get('payments/{payment}/export-pdf', [PaymentController::class, 'exportPDF'])->name('payments.export-pdf');

Route::get('test',function(){
    return view('main-page');
});

// Temporary debugging route - remove this in production
Route::get('/debug-subject-schema', function() {
    // Get the first subject
    $subject = \App\Models\Subject::first();
    // Get the table schema
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('subjects');
    
    return [
        'sample_subject' => $subject,
        'table_columns' => $columns,
    ];
});

Route::fallback(function(){
    return "<h1 style='font-family:Phetsarath OT; color:orange; margin-left: 35%; margin-top:20%;'> ບໍ່ພົບໜ້າ Website ທີ່ທ່ານຄົ້ນຫາ </h1>"; 
});



