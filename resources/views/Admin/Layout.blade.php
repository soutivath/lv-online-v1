<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        * {
            font-family: 'Noto Sans Lao', sans-serif;
            font-optical-sizing: auto;
            font-weight: <weight>;
            font-style: normal;
            font-variation-settings:
                "wdth" 100;
        }
        
        /* Student profile summary styles */
        .student-summary-card {
            transition: all 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .student-summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .student-stat-card {
            border-radius: 10px;
            transition: all 0.3s ease;
            border: none;
        }
        
        .student-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .student-profile-picture {
            border: 3px solid #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .student-profile-picture:hover {
            transform: scale(1.05);
        }
        
        /* Student dashboard table styles */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table-sm {
            font-size: 0.85rem;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }
        
        .table-striped > tbody > tr:nth-of-type(odd) > * {
            --bs-table-accent-bg: rgba(0, 0, 0, 0.02);
        }
        
        .student-summary-card .card-header {
            padding: 0.75rem 1rem;
        }
        
        .student-summary-card .table th {
            font-weight: 600;
            white-space: nowrap;
        }
        
        .student-summary-card .badge {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    {{-- Add auth check script - redirects if not logged in, but allows main page access --}}
    <script>
        // Check if user is logged in
        @if(!session()->has('user') && !request()->is('login') && !request()->is('register') && !request()->is('/'))
            window.location.href = "{{ url('/login') }}";
        @endif
    </script>

    {{-- bs icon --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- link bs5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    {{-- gg Lao font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>
    {{-- Menu bar --}}
    <nav class="navbar navbar-dark navbar-expand-lg  border-primary-subtle border-bottom border-2 sticky-top" style="background-color:#2f2cca;">
        <div class="container pt-3">
            <div class="col">
                <img src="{{ asset('assets/img/pf.jpg') }}" alt="" class=" mt-3 " style="width: 120px; border-radius:50%;">
                <a class="navbar-brand" href="#" style="font-size: 25px"> Laovieng Collage</a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown" style="font-size: 25px">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" aria-current="page" href="/">ໜ້າລັກ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.registration') ? 'active' : '' }}" href="{{ route('student.registration') }}">ລົງທະບຽນຮຽນ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.upgrade') ? 'active' : '' }}" href="{{ route('student.upgrade') }}">ອັບເກດ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.payment') ? 'active' : '' }}" href="{{ route('student.payment') }}">ຊຳລະຄ່າຮຽນ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.receipts') ? 'active' : '' }}" href="{{ route('student.receipts') }}">ໃບຮັບເງິນຂອງຂ້ອຍ</a>
                    </li>
                    {{-- Use the request session to check user authentication state --}}
                    {{-- @dd(session('user')); --}}
                    @if(session()->has('user'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i> {{ session('user')['name'] }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item {{ request()->routeIs('main') && !request()->get('view') ? 'active' : '' }}" href="{{ route('main') }}"><i class="bi bi-speedometer2 me-2"></i>ໂປຣໄຟລ໌</a></li>
                                {{-- <li><a class="dropdown-item {{ request()->get('view') == 'profile' ? 'active' : '' }}" href="{{ route('student.profile') }}"><i class="bi bi-person-badge me-2"></i>ບັດນັກສຶກສາ</a></li> --}}
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ url('/logout') }}" method="POST" id="logout-form">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>ອອກຈາກລະບົບ
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> ເຂົ້າສູ່ລະບົບ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/register') }}">
                                <i class="bi bi-person-plus me-1"></i> ລົງທະບຽນ
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    {{-- Header --}}
    <div class="container-fluid" style="background-color:#1c37e1;">
        <div class="row">
            <div class="col py-3 text-center" >
                <h1 class="text-light">ລະບົບລົງທະບຽນອອນລາຍ ວິທະຍາໄລ ລາວວຽງ</h1>
            </div>
        </div>
    </div>

    {{-- Student Profile Summary (shown when a student is logged in) --}}
    @if(session()->has('user') && request()->is('/'))
        @php
            $user = \App\Models\User::find(session('user')['id']);
            $student = \App\Models\Student::where('user_id', $user->id)->with([
                'registrations.registrationDetails.major.term',
                'registrations.registrationDetails.major.year',
                'payments.major.term',
                'payments.major.year',
                'upgrades.major.term',
                'upgrades.major.year',
                'upgrades.upgradeDetails.subject'
            ])->first();
        @endphp
        
        @if($student)
            <div class="container my-4">
                <div class="card shadow-sm border-0 mb-4 student-summary-card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="bi bi-person-vcard-fill me-2"></i>ຍິນດີຕ້ອນຮັບ, {{ $student->name }} {{ $student->sername }}</h4>
                        <span class="badge bg-light text-primary fs-6">{{ $student->id }}</span>
                    </div>
                    <div class="card-body pt-4">
                        <div class="row">
                            <div class="col-lg-3 col-md-4 text-center mb-3 mb-md-0">
                                <div class="d-flex flex-column align-items-center">
                                    @if($student->picture)
                                        <img src="{{ asset('storage/' . $student->picture) }}" alt="Student Picture" class="img-fluid rounded-circle student-profile-picture mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                                    @else
                                        <div class="border rounded-circle d-inline-flex justify-content-center align-items-center bg-light student-profile-picture mb-3" style="width: 150px; height: 150px;">
                                            <i class="bi bi-person-fill" style="font-size: 5rem; color: #adb5bd;"></i>
                                        </div>
                                    @endif
                                    
                                    <!-- Student Mini Info -->
                                    <div class="mb-3 text-start w-100">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="bi bi-person-vcard me-2 text-primary"></i>
                                            <small>{{ $student->id }}</small>
                                        </div>
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="bi bi-calendar-date me-2 text-primary"></i>
                                            <small>{{ \Carbon\Carbon::parse($student->created_at)->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                    
                                    <!-- Quick Stats -->
                                    <div class="d-flex justify-content-around w-100 border-top pt-3">
                                        <div class="text-center mx-1">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 mb-1" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-mortarboard-fill text-primary"></i>
                                            </div>
                                            <div class="small">{{ $student->registrations->count() }}</div>
                                        </div>
                                        <div class="text-center mx-1">
                                            <div class="bg-success bg-opacity-10 rounded-circle p-2 mb-1" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-cash-coin text-success"></i>
                                            </div>
                                            <div class="small">{{ $student->payments->count() }}</div>
                                        </div>
                                        <div class="text-center mx-1">
                                            <div class="bg-info bg-opacity-10 rounded-circle p-2 mb-1" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-arrow-up-circle-fill text-info"></i>
                                            </div>
                                            <div class="small">{{ $student->upgrades->count() }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-9 col-md-8">
                                <!-- Registration Tab -->
                                <div class="card mb-3">
                                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"><i class="bi bi-mortarboard-fill me-2"></i>ການລົງທະບຽນຂອງທ່ານ</h5>
                                        <span class="badge bg-white text-primary">{{ $student->registrations->count() }}</span>
                                    </div>
                                    <div class="card-body p-0">
                                        @if($student->registrations->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm table-striped table-hover mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>ວັນທີ</th>
                                                            <th>ສາຂາ</th>
                                                            <th>ປີການສຶກສາ</th>
                                                            <th>ເທີມ</th>
                                                            <th>ສະຖານະ</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($student->registrations as $registration)
                                                            <tr>
                                                                <td>{{ \Carbon\Carbon::parse($registration->date)->format('d/m/Y') }}</td>
                                                                <td>
                                                                    @if($registration->registrationDetails->count() > 0)
                                                                        {{ $registration->registrationDetails->first()->major->name }}
                                                                    @else
                                                                        ບໍ່ມີ
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($registration->registrationDetails->count() > 0 && isset($registration->registrationDetails->first()->major->year))
                                                                        {{ $registration->registrationDetails->first()->major->year->name }}
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($registration->registrationDetails->count() > 0 && isset($registration->registrationDetails->first()->major->term))
                                                                        {{ $registration->registrationDetails->first()->major->term->name }}
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($registration->payment_status == 'pending')
                                                                        <span class="badge bg-warning">ລໍຖ້າ</span>
                                                                    @else
                                                                        <span class="badge bg-success">ສຳເລັດ</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted p-3 mb-0">ບໍ່ພົບການລົງທະບຽນ</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Payment Information -->
                                <div class="card mb-3">
                                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"><i class="bi bi-cash-coin me-2"></i>ການຊຳລະເງິນຂອງທ່ານ</h5>
                                        <span class="badge bg-white text-success">{{ $student->payments->count() }}</span>
                                    </div>
                                    <div class="card-body p-0">
                                        @if($student->payments->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm table-striped table-hover mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>ວັນທີ</th>
                                                            <th>ສາຂາ</th>
                                                            <th>ປີການສຶກສາ</th>
                                                            <th>ຈຳນວນເງິນ</th>
                                                            <th>ສະຖານະ</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($student->payments as $payment)
                                                            <tr>
                                                                <td>{{ \Carbon\Carbon::parse($payment->date)->format('d/m/Y') }}</td>
                                                                <td>{{ $payment->major->name }}</td>
                                                                <td>{{ isset($payment->major->year) ? $payment->major->year->name : '-' }}</td>
                                                                <td>{{ number_format($payment->total_price, 2) }}</td>
                                                                <td>
                                                                    @if($payment->status == 'pending')
                                                                        <span class="badge bg-warning">ລໍຖ້າ</span>
                                                                    @else
                                                                        <span class="badge bg-success">ສຳເລັດ</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted p-3 mb-0">ບໍ່ພົບການຊຳລະເງິນ</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Upgrade Information -->
                                <div class="card mb-3">
                                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"><i class="bi bi-arrow-up-circle-fill me-2"></i>ການອັບເກຣດຂອງທ່ານ</h5>
                                        <span class="badge bg-white text-info">{{ $student->upgrades->count() }}</span>
                                    </div>
                                    <div class="card-body p-0">
                                        @if($student->upgrades->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm table-striped table-hover mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>ວັນທີ</th>
                                                            <th>ສາຂາ</th>
                                                            <th>ປີການສຶກສາ</th>
                                                            <th>ວິຊາຮຽນ</th>
                                                            <th>ສະຖານະ</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($student->upgrades as $upgrade)
                                                            <tr>
                                                                <td>{{ \Carbon\Carbon::parse($upgrade->date)->format('d/m/Y') }}</td>
                                                                <td>{{ $upgrade->major->name }}</td>
                                                                <td>{{ isset($upgrade->major->year) ? $upgrade->major->year->name : '-' }}</td>
                                                                <td>
                                                                    @if($upgrade->upgradeDetails->count() > 0)
                                                                        {{ $upgrade->upgradeDetails->count() }} ວິຊາ
                                                                    @else
                                                                        0 ວິຊາ
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($upgrade->payment_status == 'pending')
                                                                        <span class="badge bg-warning">ລໍຖ້າ</span>
                                                                    @else
                                                                        <span class="badge bg-success">ສຳເລັດ</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted p-3 mb-0">ບໍ່ພົບການອັບເກຣດ</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                {{-- <div class="d-flex justify-content-between mb-3">
                                    <a href="{{ route('main') }}" class="btn btn-primary">
                                        <i class="bi bi-speedometer2 me-1"></i> ກວດສອບລາຍລະອຽດ
                                    </a>
                                    <a href="{{ route('student.profile') }}" class="btn btn-outline-primary">
                                        <i class="bi bi-person-badge me-1"></i> ເບິ່ງບັດນັກສຶກສາ
                                    </a>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
    
    {{-- contents --}}
    <div class="container-fluid" style="background-color:#e4e5f0;">
        @yield('contents')
    </div>

    @if(session()->has('auth_user'))
    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="profileModalLabel">ໂປຣໄຟລ໌ຜູ້ໃຊ້</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="avatar-circle mx-auto mb-3">
                            <span class="avatar-initials">{{ substr(session('auth_user')['name'], 0, 1) }}</span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="profile-info">
                                <i class="bi bi-person-badge fs-4 text-primary profile-icon"></i>
                                <div>
                                    <label class="form-label text-muted mb-0">ຊື່</label>
                                    <h5>{{ session('auth_user')['name'] }}</h5>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <div class="profile-info">
                                <i class="bi bi-envelope fs-4 text-primary profile-icon"></i>
                                <div>
                                    <label class="form-label text-muted mb-0">ອີເມວ</label>
                                    <h5>{{ session('auth_user')['email'] }}</h5>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="profile-info">
                                <i class="bi bi-calendar-check fs-4 text-primary profile-icon"></i>
                                <div>
                                    <label class="form-label text-muted mb-0">ວັນທີ່ສ້າງບັນຊີ</label>
                                    <h5>{{ session('auth_user')['created_at'] }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ປິດ</button>
                    <a href="#" class="btn btn-primary">ແກ້ໄຂໂປຣໄຟລ໌</a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        .avatar-circle {
            width: 100px;
            height: 100px;
            background-color: #0d6efd;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .avatar-initials {
            color: white;
            font-size: 48px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .profile-info {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }
        
        .profile-icon {
            background-color: rgba(13, 110, 253, 0.1);
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }
    </style>

    {{-- link bs_js5 --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
