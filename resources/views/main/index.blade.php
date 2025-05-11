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
    </script>
</body>
</html>
