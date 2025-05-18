<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Laovieng College</title>
    
    {{-- Admin/Employee Redirection Check - Using relationship check instead of role --}}
    @if(isset($user) && $user->employee)
        <script>
            window.location.href = "{{ route('admin.dashboard') }}";
        </script>
    @endif
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
    <style>
        .profile-section {
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .student-picture {
            max-width: 150px;
            margin-bottom: 15px;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: bold;
            transition: all 0.2s ease;
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }
        
        .logo-icon {
            font-size: 1.8rem;
            margin-right: 0.5rem;
        }
        
        /* Student ID Card Styles */
        .student-id-card {
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            background-color: white;
            transition: all 0.3s ease;
        }
        
        .student-id-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        
        .id-card-header {
            background: linear-gradient(135deg, #2f2cca 0%, #1c37e1 100%);
            color: white;
            padding: 15px;
            display: flex;
            align-items: center;
            border-bottom: 3px solid #ffcc00;
        }
        
        .college-logo {
            background-color: rgba(255,255,255,0.2);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 15px;
            font-size: 24px;
        }
        
        .header-text h5 {
            margin: 0;
            font-weight: bold;
            letter-spacing: 1px;
        }
        
        .header-text p.small {
            margin: 0;
            font-size: 0.8rem;
            opacity: 0.8;
        }
        
        .id-card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: white;
        }
        
        .student-photo {
            width: 120px;
            height: 120px;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 15px;
            border: 2px solid #2f2cca;
        }
        
        .student-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .no-photo {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f2f2f2;
            color: #888;
        }
        
        .student-details {
            width: 100%;
        }
        
        .student-name {
            color: #333;
            font-weight: bold;
            margin: 0;
        }
        
        .student-id {
            background-color: #2f2cca;
            color: white;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.9rem;
            display: inline-block;
            margin: 5px 0;
        }
        
        .details-list {
            margin: 15px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        
        .detail-item {
            display: flex;
            padding: 6px 0;
            border-bottom: 1px dashed #ddd;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            color: #666;
            width: 45%;
            font-size: 0.9rem;
        }
        
        .detail-label i {
            width: 20px;
            color: #2f2cca;
        }
        
        .detail-value {
            font-weight: 500;
            color: #333;
            font-size: 0.9rem;
        }
        
        .barcode-container {
            text-align: center;
            margin-top: 10px;
        }
        
        .barcode {
            padding: 5px 10px;
            border: 1px solid #ddd;
            display: inline-block;
            border-radius: 4px;
            background-color: #fff;
        }
        
        .barcode-number {
            font-family: monospace;
            letter-spacing: 2px;
            font-size: 0.9rem;
            margin-left: 8px;
        }
        
        .id-card-footer {
            padding: 15px;
            background-color: #f8f9fa;
            font-size: 0.8rem;
            border-top: 1px solid #eee;
        }
        
        .contact-info {
            color: #555;
            margin-bottom: 8px;
        }
        
        .contact-info div {
            margin-bottom: 5px;
        }
        
        .contact-info i {
            color: #2f2cca;
            width: 15px;
            margin-right: 5px;
        }
        
        .expiry-info {
            text-align: right;
            font-style: italic;
            color: #888;
            font-size: 0.75rem;
        }
        
        .valid-until {
            color: #FF5722;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/" title="Go to Home Page">
                <i class="fas fa-school logo-icon"></i>
                Laovieng College
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            {{ Session::get('user')['name'] }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item {{ request()->routeIs('main') && !request()->get('view') ? 'active' : '' }}" href="{{ route('main') }}">
                                <i class="fas fa-tachometer-alt me-1"></i> ໂປຣໄຟລ໌
                            </a></li>
                            {{-- <li><a class="dropdown-item {{ request()->get('view') == 'profile' ? 'active' : '' }}" href="{{ route('main', ['view' => 'profile']) }}">
                                <i class="fas fa-id-card me-1"></i> Profile
                            </a></li> --}}
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}">
                                <i class="fas fa-sign-out-alt me-1"></i> Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="true">
                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                </button>
            </li>
            {{-- <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">
                    <i class="fas fa-id-card me-1"></i> Profile
                </button>
            </li> --}}
        </ul>
        
        <div class="tab-content" id="myTabContent">
            <!-- Dashboard Tab -->
            <div class="tab-pane fade show active" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                <div class="row">
                    <div class="col-md-4">
                        <div class="profile-section">
                            <div class="text-center">
                                @if($student->picture)
                                    <img src="{{ asset('storage/' . $student->picture) }}" alt="Student Picture" class="img-fluid rounded-circle student-picture">
                                @else
                                    <div class="border rounded-circle d-inline-flex justify-content-center align-items-center bg-light student-picture" style="width: 150px; height: 150px;">
                                        <i class="fas fa-user fa-5x text-secondary"></i>
                                    </div>
                                @endif
                            </div>
                            <h4 class="text-center mb-3">{{ $student->name }} {{ $student->sername }}</h4>
                            <hr>
                            <p><strong><i class="fas fa-id-card me-2"></i>ລະຫັດ:</strong> {{ $student->id }}</p>
                            <p><strong><i class="fas fa-venus-mars me-2"></i>ເພດ:</strong> {{ $student->gender }}</p>
                            <p><strong><i class="fas fa-birthday-cake me-2"></i>ວັນເດືອນປີເກີດ:</strong> {{ \Carbon\Carbon::parse($student->birthday)->format('d/m/Y') }}</p>
                            <p><strong><i class="fas fa-flag me-2"></i>ສັນຊາດ:</strong> {{ $student->nationality }}</p>
                            <p><strong><i class="fas fa-phone me-2"></i>ເບີໂທລະສັບ:</strong> {{ $student->tell }}</p>
                            <p><strong><i class="fas fa-map-marker-alt me-2"></i>ທີ່ຢູ່:</strong> {{ $student->address }}</p>
                        </div>
                    </div>
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>ການລົງທະບຽນຂອງທ່ານ</h5>
                    </div>
                    <div class="card-body">
                        @if($student->registrations->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ວັນທີ</th>
                                            <th>ສາຂາ</th>
                                            <th>ຈຳນວນເງິນ</th>
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
                                                        @if($registration->registrationDetails->count() > 1)
                                                            <span class="badge bg-info">+{{ $registration->registrationDetails->count() - 1 }}</span>
                                                        @endif
                                                    @else
                                                        ບໍ່ມີ
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($registration->registrationDetails->count() > 0)
                                                        {{ number_format($registration->registrationDetails->sum('total_price'), 2) }}
                                                    @else
                                                        ບໍ່ມີ
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
                            <p class="text-muted">ບໍ່ພົບການລົງທະບຽນ</p>
                        @endif
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>ການຊຳລະເງິນຂອງທ່ານ</h5>
                    </div>
                    <div class="card-body">
                        @if($student->payments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ວັນທີ</th>
                                            <th>ສາຂາ</th>
                                            <th>ຈຳນວນເງິນ</th>
                                            <th>ສະຖານະ</th>
                                            <th>ຄຳສັ່ງ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($student->payments as $payment)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($payment->date)->format('d/m/Y') }}</td>
                                                <td>{{ $payment->major->name }}</td>
                                                <td>{{ number_format($payment->total_price, 2) }}</td>
                                                <td>
                                                    @if($payment->status == 'pending')
                                                        <span class="badge bg-warning">ລໍຖ້າ</span>
                                                    @else
                                                        <span class="badge bg-success">ສຳເລັດ</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('payments.export-pdf', $payment->id) }}" class="btn btn-sm btn-success" target="_blank">
                                                        <i class="fas fa-file-invoice"></i> Receipt
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">No payments found.</p>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-arrow-up me-2"></i>ການອັບເກຣດຂອງທ່ານ</h5>
                    </div>
                    <div class="card-body">
                        @if($student->upgrades->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ວັນທີ</th>
                                            <th>ສາຂາ</th>
                                            <th>ວິຊາຮຽນ</th>
                                            <th>ສະຖານະ</th>
                                            <th>ໃບຮັບເງິນ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($student->upgrades as $upgrade)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($upgrade->date)->format('d/m/Y') }}</td>
                                                <td>{{ $upgrade->major->name }}</td>
                                                <td>{{ $upgrade->upgradeDetails->count() }}</td>
                                                <td>
                                                    @if($upgrade->payment_status == 'pending')
                                                        <span class="badge bg-warning">ລໍຖ້າ</span>
                                                    @else
                                                        <span class="badge bg-success">ສຳເລັດ</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('upgrades.export-pdf', $upgrade->id) }}" class="btn btn-sm btn-info" target="_blank">
                                                        <i class="fas fa-file-pdf"></i> Details
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">ບໍ່ພົບການອັບເກຣດ</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Dashboard Tab -->
        
        <!-- Profile Tab -->
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <h2 class="page-title mb-4"><i class="fas fa-user-circle me-2"></i>Student Profile</h2>
            
            <div class="row">
                <div class="col-md-4">
                    <!-- Student ID Card -->
                    <div class="student-id-card mb-4">
                        <div class="id-card-header">
                            <div class="college-logo">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="header-text">
                                <h5>Laovieng College</h5>
                                <p class="small text-white">Student Identification Card</p>
                                <p class="small text-white">ບັດນັກສຶກສາ</p>
                            </div>
                        </div>
                        
                        <div class="id-card-body">
                            <div class="student-photo">
                                @if($student->picture)
                                    <img src="{{ asset('storage/' . $student->picture) }}" alt="Student Picture" class="img-fluid">
                                @else
                                    <div class="no-photo">
                                        <i class="fas fa-user fa-3x"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="student-details">
                                <div class="text-center mb-2">
                                    <h5 class="student-name">{{ $student->name }} {{ $student->sername }}</h5>
                                    <div class="student-id">{{ $student->id }}</div>
                                </div>
                                
                                <div class="details-list">
                                    <div class="detail-item">
                                        <span class="detail-label"><i class="fas fa-venus-mars"></i> ເພດ:</span>
                                        <span class="detail-value">{{ $student->gender }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label"><i class="fas fa-birthday-cake"></i> ເກີດວັນທີ:</span>
                                        <span class="detail-value">{{ \Carbon\Carbon::parse($student->birthday)->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label"><i class="fas fa-flag"></i> ສັນຊາດ:</span>
                                        <span class="detail-value">{{ $student->nationality }}</span>
                                    </div>
                                </div>
                                
                                <div class="barcode-container">
                                    <div class="barcode">
                                        <i class="fas fa-barcode fa-lg"></i>
                                        <span class="barcode-number">{{ $student->id }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="id-card-footer">
                            <div class="contact-info">
                                <div><i class="fas fa-phone"></i> {{ $student->tell }}</div>
                                <div><i class="fas fa-map-marker-alt"></i> {{ $student->address }}</div>
                            </div>
                            <div class="expiry-info">
                                <div class="valid-until">Valid until: 31/12/2025</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional student information section -->
                    <div class="profile-section">
                        <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-info-circle me-2"></i>ຂໍ້ມູນເພີ່ມເຕີມ</h5>
                        <p><strong><i class="fas fa-phone me-2"></i>ເບີໂທລະສັບ:</strong> {{ $student->tell }}</p>
                        <p><strong><i class="fas fa-map-marker-alt me-2"></i>ທີ່ຢູ່:</strong> {{ $student->address }}</p>
                        <p><strong><i class="fas fa-calendar-alt me-2"></i>ວັນທີລົງທະບຽນ:</strong> {{ \Carbon\Carbon::parse($student->created_at)->format('d/m/Y') }}</p>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>ການລົງທະບຽນຂອງທ່ານ</h5>
                        </div>
                        <div class="card-body">
                            @if($student->registrations->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ວັນທີ</th>
                                                <th>ສາຂາ</th>
                                                <th>ປີການສຶກສາ</th>
                                                <th>ເທີມ</th>
                                                <th>ຈຳນວນເງິນ</th>
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
                                                            @if($registration->registrationDetails->count() > 1)
                                                                <span class="badge bg-info">+{{ $registration->registrationDetails->count() - 1 }}</span>
                                                            @endif
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
                                                        @if($registration->registrationDetails->count() > 0)
                                                            {{ number_format($registration->registrationDetails->sum('total_price'), 2) }}
                                                        @else
                                                            ບໍ່ມີ
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
                                <p class="text-muted">ບໍ່ພົບການລົງທະບຽນ</p>
                            @endif
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>ການຊຳລະເງິນຂອງທ່ານ</h5>
                        </div>
                        <div class="card-body">
                            @if($student->payments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ວັນທີ</th>
                                                <th>ສາຂາ</th>
                                                <th>ປີການສຶກສາ</th>
                                                <th>ເທີມ</th>
                                                <th>ຈຳນວນເງິນ</th>
                                                <th>ສະຖານະ</th>
                                                <th>ຄຳສັ່ງ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($student->payments as $payment)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($payment->date)->format('d/m/Y') }}</td>
                                                    <td>{{ $payment->major->name }}</td>
                                                    <td>{{ isset($payment->major->year) ? $payment->major->year->name : '-' }}</td>
                                                    <td>{{ isset($payment->major->term) ? $payment->major->term->name : '-' }}</td>
                                                    <td>{{ number_format($payment->total_price, 2) }}</td>
                                                    <td>
                                                        @if($payment->status == 'pending')
                                                            <span class="badge bg-warning">ລໍຖ້າ</span>
                                                        @else
                                                            <span class="badge bg-success">ສຳເລັດ</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('payments.export-pdf', $payment->id) }}" class="btn btn-sm btn-success" target="_blank">
                                                            <i class="fas fa-file-invoice"></i> Receipt
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">No payments found.</p>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-arrow-up me-2"></i>ການອັບເກຣດຂອງທ່ານ</h5>
                        </div>
                        <div class="card-body">
                            @if($student->upgrades->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ວັນທີ</th>
                                                <th>ສາຂາ</th>
                                                <th>ປີການສຶກສາ</th>
                                                <th>ເທີມ</th>
                                                <th>ວິຊາຮຽນ</th>
                                                <th>ສະຖານະ</th>
                                                <th>ໃບຮັບເງິນ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($student->upgrades as $upgrade)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($upgrade->date)->format('d/m/Y') }}</td>
                                                    <td>{{ $upgrade->major->name }}</td>
                                                    <td>{{ isset($upgrade->major->year) ? $upgrade->major->year->name : '-' }}</td>
                                                    <td>{{ isset($upgrade->major->term) ? $upgrade->major->term->name : '-' }}</td>
                                                    <td>
                                                        @if($upgrade->upgradeDetails->count() > 0)
                                                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#upgradeModal{{ $upgrade->id }}">
                                                                {{ $upgrade->upgradeDetails->count() }} ວິຊາ
                                                            </button>
                                                            
                                                            <!-- Modal for showing subjects -->
                                                            <div class="modal fade" id="upgradeModal{{ $upgrade->id }}" tabindex="-1" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-info text-white">
                                                                            <h5 class="modal-title">ລາຍຊື່ວິຊາທີ່ອັບເກຣດ</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <ul class="list-group">
                                                                                @foreach($upgrade->upgradeDetails as $detail)
                                                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                        {{ isset($detail->subject) ? $detail->subject->name : 'Unknown Subject' }}
                                                                                        <span class="badge bg-primary rounded-pill">{{ number_format($detail->price, 2) }}</span>
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
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
                                                    <td>
                                                        <a href="{{ route('upgrades.export-pdf', $upgrade->id) }}" class="btn btn-sm btn-info" target="_blank">
                                                            <i class="fas fa-file-pdf"></i> Details
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">ບໍ່ພົບການອັບເກຣດ</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Profile Tab -->
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>

    <script>
        @if(session('sweet_alert'))
            // Only show SweetAlert for fresh page loads, not when navigating back
            let alreadyShown = sessionStorage.getItem('alert_shown_{{ session()->getId() }}');
            
            if (!alreadyShown) {
                Swal.fire({
                    icon: '{{ session('sweet_alert.type') }}',
                    title: '{{ session('sweet_alert.title') }}',
                    text: '{{ session('sweet_alert.text') }}',
                    timer: 3000,
                    timerProgressBar: true
                });
                
                // Mark this alert as shown in this session
                sessionStorage.setItem('alert_shown_{{ session()->getId() }}', 'true');
            }
            
            // Clear the session storage item when leaving the page
            window.addEventListener('beforeunload', function() {
                sessionStorage.removeItem('alert_shown_{{ session()->getId() }}');
            });
        @endif
        
        // Check if we should show the profile tab based on URL parameters
        document.addEventListener('DOMContentLoaded', function() {
            // Check for URL parameter or hash
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('view') && urlParams.get('view') === 'profile' || window.location.hash === '#profile') {
                document.getElementById('profile-tab').click();
            }
            
            // Add hash-based navigation for tabs
            const dashboardTab = document.getElementById('dashboard-tab');
            const profileTab = document.getElementById('profile-tab');
            
            dashboardTab.addEventListener('click', function() {
                window.location.hash = 'dashboard';
            });
            
            profileTab.addEventListener('click', function() {
                window.location.hash = 'profile';
            });
            
            // Check hash on page load
            if(window.location.hash === '#profile') {
                document.getElementById('profile-tab').click();
            }
            
            // Add hover animation effect to student ID card
            const studentIdCard = document.querySelector('.student-id-card');
            if (studentIdCard) {
                studentIdCard.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px) rotateY(5deg)';
                });
                
                studentIdCard.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) rotateY(0)';
                });
            }
        });
        
        @if(session('debug_info'))
            console.log('Debug Info:', @json(session('debug_info')));
        @endif
    </script>
    
    @if(session('debug_info'))
    <div class="container mt-3" style="background-color: #f8d7da; padding: 10px; border-radius: 5px;">
        <h5>Debug Information:</h5>
        <pre>{{ json_encode(session('debug_info'), JSON_PRETTY_PRINT) }}</pre>
    </div>
    @endif
</body>
</html>
