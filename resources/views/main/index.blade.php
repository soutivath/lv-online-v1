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
      <!-- Bootstrap CSS -->    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">    <!-- Student ID Card Enhanced Styles -->
    <link rel="stylesheet" href="{{ asset('css/student-id-card.css') }}">    <style>
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
        .student-profile-picture {
            border: 3px solid #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .student-profile-picture:hover {
            transform: scale(1.05);
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
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            perspective: 1000px;
            transform-style: preserve-3d;
            position: relative;
        }
        
        .student-id-card:hover {
            transform: translateY(-8px) rotateX(5deg) rotateY(5deg);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2), 0 0 25px rgba(47,44,202,0.3);
        }
        
        .student-id-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0) 50%);
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.5s ease;
            border-radius: 12px;
            z-index: 1;
        }
        
        .student-id-card:hover::before {
            opacity: 1;
        }
        
        .id-card-header {
            background: linear-gradient(135deg, #2f2cca 0%, #1c37e1 100%);
            color: white;
            padding: 15px;
            display: flex;
            align-items: center;
            border-bottom: 3px solid #ffcc00;
            position: relative;
            overflow: hidden;
        }
        
        .id-card-header::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%);
            opacity: 0;
            transform: scale(0.5);
            transition: transform 0.5s ease, opacity 0.5s ease;
            pointer-events: none;
        }
        
        .student-id-card:hover .id-card-header::after {
            opacity: 0.7;
            transform: scale(1);
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
            position: relative;
            z-index: 1;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .student-id-card:hover .college-logo {
            transform: rotate(15deg) scale(1.1);
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }
        
        .header-text h5 {
            margin: 0;
            font-weight: bold;
            letter-spacing: 1px;
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }
        
        .student-id-card:hover .header-text h5 {
            transform: translateY(-2px);
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        
        .header-text p.small {
            margin: 0;
            font-size: 0.8rem;
            opacity: 0.8;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }
        
        .student-id-card:hover .header-text p.small {
            opacity: 1;
        }
        
        .id-card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: white;
            position: relative;
            overflow: hidden;
        }
        
        .student-photo {
            width: 120px;
            height: 120px;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 15px;
            border: 2px solid #2f2cca;
            transition: all 0.5s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .student-id-card:hover .student-photo {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        
        .student-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .student-id-card:hover .student-photo img {
            transform: scale(1.1);
        }
        
        .no-photo {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f2f2f2;
            color: #888;
            transition: all 0.3s ease;
        }
        
        .student-id-card:hover .no-photo {
            background-color: #e9ecef;
            color: #555;
        }
        
        .student-details {
            width: 100%;
            position: relative;
            z-index: 1;
        }
        
        .student-name {
            color: #333;
            font-weight: bold;
            margin: 0;
            transition: all 0.3s ease;
        }
        
        .student-id-card:hover .student-name {
            transform: translateY(-2px);
            color: #2f2cca;
        }
        
        .student-id {
            background: linear-gradient(45deg, #2f2cca, #1a27b9);
            color: white;
            padding: 3px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-block;
            margin: 5px 0;
            transition: all 0.4s ease;
            box-shadow: 0 2px 10px rgba(47,44,202,0.2);
        }
        
        .student-id-card:hover .student-id {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(47,44,202,0.4);
            background: linear-gradient(45deg, #1a27b9, #2f2cca);
        }
        
        .details-list {
            margin: 15px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .student-id-card:hover .details-list {
            transform: translateY(-2px);
            background-color: #f0f3f9;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .detail-item {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px dashed #ddd;
            transition: all 0.2s ease;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .student-id-card:hover .detail-item:hover {
            background-color: #f0f7ff;
            transform: translateX(3px);
            padding-left: 5px;
            border-radius: 4px;
        }
        
        .detail-label {
            color: #666;
            width: 45%;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }
        
        .student-id-card:hover .detail-item:hover .detail-label {
            color: #2f2cca;
        }
        
        .detail-label i {
            width: 20px;
            color: #2f2cca;
            transition: all 0.3s ease;
        }
        
        .student-id-card:hover .detail-item:hover .detail-label i {
            transform: scale(1.2);
        }
        
        .detail-value {
            font-weight: 500;
            color: #333;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .student-id-card:hover .detail-item:hover .detail-value {
            font-weight: 600;
            color: #1c1c1c;
        }
        
        .barcode-container {
            text-align: center;
            margin-top: 15px;
            position: relative;
        }
        
        .barcode {
            padding: 8px 15px;
            border: 1px solid #ddd;
            display: none;
            border-radius: 4px;
            background-color: #fff;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .student-id-card:hover .barcode {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-color: #bbb;
        }
        
        /* Barcode scanline animation */
        .barcode::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(90deg, 
                rgba(255,255,255,0) 0%, 
                rgba(255,255,255,0.8) 50%, 
                rgba(255,255,255,0) 100%);
            transform: skewX(-25deg);
            opacity: 0;
            visibility: hidden;
            transition: all 0.05s;
        }
        
        .student-id-card:hover .barcode::after {
            animation: scanline 1.5s ease-in-out infinite;
            opacity: 1;
            visibility: visible;
        }
        
        @keyframes scanline {
            0% {
                left: -100%;
            }
            100% {
                left: 200%;
            }
        }
        
        .barcode-number {
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
            font-size: 0.9rem;
            margin-left: 8px;
            font-weight: 600;
        }
        
        /* QR Code Styles */
        .qr-container {
            display: flex;
            justify-content: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px dashed #eee;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .student-id-card:hover .qr-container {
            border-top: 1px dashed #ccc;
        }
        
        .qr-code {
            max-width: 100px;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            padding: 8px;
            background: white;
            border: 1px solid #eee;
            border-radius: 8px;
            position: relative;
            z-index: 2;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .qr-code::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 8px;
            background: linear-gradient(135deg, #2f2cca 0%, #4a47e3 100%);
            z-index: -1;
            opacity: 0;
            transform: scale(1.15);
            transition: all 0.4s ease;
        }
        
        .student-id-card:hover .qr-code {
            transform: translateY(-5px) scale(1.1) rotate(2deg);
            box-shadow: 0 15px 25px rgba(0,0,0,0.15), 0 0 15px rgba(47,44,202,0.2);
            padding: 10px;
        }
        
        .student-id-card:hover .qr-code::before {
            opacity: 0.1;
            transform: scale(1);
        }
        
        .qr-caption {
            font-size: 0.8rem;
            color: #666;
            text-align: center;
            margin-top: 8px;
            transition: all 0.3s ease;
            opacity: 0.8;
            font-weight: 500;
        }
        
        .student-id-card:hover .qr-caption {
            transform: translateY(2px);
            color: #2f2cca;
            opacity: 1;
        }
        
        .id-card-footer {
            padding: 15px;
            background-color: #f8f9fa;
            font-size: 0.8rem;
            border-top: 1px solid #eee;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .student-id-card:hover .id-card-footer {
            background-color: #f0f3f9;
        }
        
        .contact-info {
            transition: all 0.3s ease;
        }
        
        .student-id-card:hover .contact-info {
            transform: translateY(-2px);
        }
        
        .expiry-info {
            transition: all 0.3s ease;
        }
        
        .student-id-card:hover .expiry-info {
            transform: translateY(-2px);
        }
        
        .valid-until {
            position: relative;
            display: none;
            font-weight: bold;
            color: #FF5722;
            transition: all 0.3s ease;
        }
        
        .student-id-card:hover .valid-until {
            text-shadow: 0 2px 8px rgba(255,87,34,0.2);
        }

        /* Add flip card functionality */
        .flip-card {
            perspective: 1000px;
            cursor: pointer;
        }
        
        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform-style: preserve-3d;
        }
        
        .flip-card.flipped .flip-card-inner {
            transform: rotateY(180deg);
        }
        
        .flip-card-front, .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden; /* Safari */
            backface-visibility: hidden;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .flip-card-front {
            background-color: white;
        }
        
        .flip-card-back {
            transform: rotateY(180deg);
            background: linear-gradient(135deg, #2f2cca 0%, #1c37e1 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
        }
        
        .flip-hint {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: rgba(47, 44, 202, 0.8);
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            z-index: 10;
            opacity: 0.7;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .flip-hint:hover {
            opacity: 1;
            transform: scale(1.1);
        }
        
        .back-content {
            width: 100%;
        }
        
        .back-header {
            margin-bottom: 20px;
        }
        
        .back-logo {
            font-size: 40px;
            margin-bottom: 10px;
        }
        
        .back-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .back-subtitle {
            font-size: 14px;
            opacity: 0.8;
        }
        
        .scan-code-container {
            margin: 20px 0;
        }
        
        .scan-code-title {
            font-size: 14px;
            margin-bottom: 15px;
            color: rgba(255,255,255,0.9);
        }
        
        .back-footer {
            font-size: 12px;
            margin-top: 20px;
            opacity: 0.7;
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
            </button>            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            @php
                                $student = \App\Models\Student::where('user_id', Session::get('user')['id'])->first();
                            @endphp
                            
                            @if($student && $student->picture)
                                <img src="{{ asset('storage/' . $student->picture) }}" alt="Profile Picture" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover; border: 2px solid white;">
                            @else
                                <div class="rounded-circle border me-2 d-inline-flex justify-content-center align-items-center bg-light" style="width: 32px; height: 32px; border: 2px solid white !important;">
                                    <i class="bi bi-person-fill" style="font-size: 1.2rem; color: #adb5bd;"></i>
                                </div>
                            @endif
                            
                            {{ Session::get('user')['name'] }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item {{ request()->routeIs('main') && !request()->get('view') ? 'active' : '' }}" href="{{ route('main') }}">
                                <i class="fas fa-tachometer-alt me-1"></i> Dashboard
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
        
        <div class="tab-content" id="myTabContent">            <!-- Dashboard Tab -->            <div class="tab-pane fade show active" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">                <div class="row justify-content-center">
                    <div class="col-md-8">                        <!-- Student Profile Header -->
                        {{-- <div class="card mb-4 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>ໂປຣໄຟລ໌ນັກສຶກສາ</h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    @if($student->picture)
                                        <img src="{{ asset('storage/' . $student->picture) }}" alt="Student Picture" class="img-fluid rounded-circle student-profile-picture mb-3" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                                    @else
                                        <div class="border rounded-circle d-inline-flex justify-content-center align-items-center bg-light student-profile-picture mb-3" style="width: 150px; height: 150px; border: 3px solid #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                                            <i class="bi bi-person-fill" style="font-size: 5rem; color: #adb5bd;"></i>
                                        </div>
                                    @endif
                                    
                                    <h4>{{ $student->name }} {{ $student->sername }}</h4>
                                    <p class="badge bg-primary mb-2">{{ $student->id }}</p>
                                </div>
                            </div>
                        </div> --}}
                        
                        <!-- Student ID Card -->
                        @include('main.dashboard-card')
                        
                        <!-- Additional student information section -->
                        {{-- <div class="profile-section">
                            <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-info-circle me-2"></i>ຂໍ້ມູນເພີ່ມເຕີມ</h5>
                            <p><strong><i class="fas fa-phone me-2"></i>ເບີໂທລະສັບ:</strong> {{ $student->tell }}</p>
                            <p><strong><i class="fas fa-map-marker-alt me-2"></i>ທີ່ຢູ່:</strong> {{ $student->address }}</p>
                            <p><strong><i class="fas fa-calendar-alt me-2"></i>ວັນທີລົງທະບຽນ:</strong> {{ \Carbon\Carbon::parse($student->created_at)->format('d/m/Y') }}</p>
                        </div> --}}
                        
                        {{-- <div class="card mb-4"> --}}
                            {{-- <div class="card-header bg-primary text-white"> --}}
                                {{-- <h5 class="mb-0"><i class="fas fa-link me-2"></i>ລິ້ງປະໂຫຍດ</h5> --}}
                            {{-- </div> --}}
                            {{-- <div class="card-body"> --}}
                                {{-- <div class="row"> --}}
                                    {{-- <div class="col-md-4 mb-3">
                                        <a href="{{ route('student.registration') }}" class="btn btn-outline-primary w-100 py-3">
                                            <i class="fas fa-graduation-cap fa-2x mb-2"></i><br>
                                            ລົງທະບຽນຮຽນ
                                        </a>
                                    </div> --}}
                                    {{-- <div class="col-md-4 mb-3">
                                        <a href="{{ route('student.payment') }}" class="btn btn-outline-success w-100 py-3">
                                            <i class="fas fa-money-bill-wave fa-2x mb-2"></i><br>
                                            ຊຳລະຄ່າຮຽນ
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="{{ route('student.upgrade') }}" class="btn btn-outline-info w-100 py-3">
                                            <i class="fas fa-arrow-up fa-2x mb-2"></i><br>
                                            ອັບເກຣດວິຊາ
                                        </a>
                                    </div> --}}
                                {{-- </div> --}}
                            {{-- </div> --}}
                        {{-- </div> --}}
                        
                        {{-- <div class="card"> --}}
                            {{-- <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i>ໃບຮັບເງິນ</h5>
                            </div> --}}
                            {{-- <div class="card-body"> --}}
                                {{-- <a href="{{ route('student.receipts') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-file-pdf me-2"></i> ເບິ່ງໃບຮັບເງິນທັງໝົດ
                                </a> --}}
                            {{-- </div> --}}
                        {{-- </div> --}}
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
                        @include('main.dashboard-card')
                        
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
            </div>
            <!-- End of Profile Tab -->
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <!-- Student ID Card Enhancement -->
    <script src="{{ asset('js/student-id-card.js') }}"></script>

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
            if ((urlParams.has('view') && urlParams.get('view') === 'profile') || window.location.hash === '#profile') {
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
            }            // Enhanced animations for student ID cards
            const studentIdCards = document.querySelectorAll('.student-id-card');
            studentIdCards.forEach(function(studentIdCard) {
                if (studentIdCard) {
                    // Track mouse position for 3D effect
                    studentIdCard.addEventListener('mousemove', function(e) {
                        const card = this;
                        const cardRect = card.getBoundingClientRect();
                        
                        // Calculate mouse position relative to card center
                        const cardCenterX = cardRect.left + cardRect.width / 2;
                        const cardCenterY = cardRect.top + cardRect.height / 2;
                        
                        // Calculate rotation based on mouse position
                        const rotateY = ((e.clientX - cardCenterX) / (cardRect.width / 2)) * 5;
                        const rotateX = -((e.clientY - cardCenterY) / (cardRect.height / 2)) * 5;
                        
                        // Apply the 3D transform
                        card.style.transform = `translateY(-8px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
                        
                        // Add dynamic highlight effect based on mouse position
                        const highlight = card.querySelector('.id-card-header::after');
                        if (highlight) {
                            const x = ((e.clientX - cardRect.left) / cardRect.width) * 100;
                            const y = ((e.clientY - cardRect.top) / cardRect.height) * 100;
                            highlight.style.background = `radial-gradient(circle at ${x}% ${y}%, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0) 70%)`;
                        }
                    });
                    
                    studentIdCard.addEventListener('mouseenter', function() {
                        this.style.boxShadow = '0 15px 35px rgba(0,0,0,0.2), 0 0 25px rgba(47,44,202,0.3)';
                    });
                    
                    studentIdCard.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0) rotateX(0) rotateY(0)';
                        this.style.boxShadow = '0 10px 30px rgba(0,0,0,0.1)';
                    });
                    
                    // Add click interaction for a subtle "press" effect
                    studentIdCard.addEventListener('mousedown', function() {
                        this.style.transform = 'translateY(-5px) scale(0.98)';
                    });
                    
                    studentIdCard.addEventListener('mouseup', function() {
                        this.style.transform = 'translateY(-8px) scale(1)';
                    });
                }
            });
              // QR code animations have been removed// QR pulse animation CSS has been removed
            
            // Add flip card functionality
            const flipCards = document.querySelectorAll('.flip-card');
            flipCards.forEach(function(flipCard) {
                if (flipCard) {
                    // Toggle flip on click
                    flipCard.addEventListener('click', function() {
                        this.classList.toggle('flipped');
                    });
                    
                    // Add flip hint functionality
                    const flipHint = flipCard.querySelector('.flip-hint');
                    if (flipHint) {
                        flipHint.addEventListener('click', function(e) {
                            e.stopPropagation(); // Prevent the flip-card click event
                            flipCard.classList.toggle('flipped');
                        });
                    }
                    
                    // Reset rotation when flipped to avoid interference with 3D effects
                    flipCard.addEventListener('transitionend', function(e) {
                        if (e.propertyName === 'transform') {
                            if (this.classList.contains('flipped')) {
                                const frontCard = this.querySelector('.student-id-card');
                                if (frontCard) {
                                    frontCard.style.transform = 'none';
                                }
                            }
                        }
                    });
                }
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
