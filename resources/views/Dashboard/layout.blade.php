<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ໜ້າຄວບຄຸມ') | ຜູ້ບໍລິຫານ</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .content {
            padding: 20px;
        }
        .active-link {
            background-color: #0d6efd;
            color: white !important;
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 bg-white text-dark p-0 min-vh-100 border-end">
            <div class="p-3">
                <h4>ໜ້າຄວບຄຸມບໍລິຫານ</h4>
                @if(session('user.role'))
                <div class="mt-2">
                    <span class="badge {{ session('user.role') == 'admin' ? 'bg-primary' : 'bg-info' }}">
                        {{ session('user.role') == 'admin' ? 'ຜູ້ບໍລິຫານ' : 'ອາຈານ' }}
                    </span>
                </div>
                @endif
            </div>
            <nav class="nav flex-column">
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->is('dashboard') ? 'active-link' : 'text-dark' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2 text-primary"></i> ໜ້າຫຼັກ
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->is('students*') ? 'active-link' : 'text-dark' }}" href="{{ route('students.index') }}">
                            <i class="fas fa-user-graduate me-2 text-primary"></i> ນັກສຶກສາ
                        </a>
                    </li>
                    
                    @if(session('user.role') == 'admin')
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->is('employees*') ? 'active-link' : 'text-dark' }}" href="{{ route('employees.index') }}">
                            <i class="fas fa-user-tie me-2 text-primary"></i> ພະນັກງານ
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->is('majors*') ? 'active-link' : 'text-dark' }}" href="{{ route('majors.index') }}">
                            <i class="fas fa-book me-2 text-primary"></i> ສາຂາຮຽນ
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->is('subjects*') ? 'active-link' : 'text-dark' }}" href="{{ route('subjects.index') }}">
                            <i class="fas fa-book-open me-2 text-primary"></i> ວິຊາຮຽນ
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->is('registrations*') ? 'active-link' : 'text-dark' }}" href="{{ route('registrations.index') }}">
                            <i class="fas fa-clipboard-list me-2 text-primary"></i> ການລົງທະບຽນ
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->is('payments*') ? 'active-link' : 'text-dark' }}" href="{{ route('payments.index') }}">
                            <i class="fas fa-money-bill me-2 text-primary"></i> ການຊຳລະເງິນ
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->is('upgrades*') ? 'active-link' : 'text-dark' }}" href="{{ route('upgrades.index') }}">
                            <i class="fas fa-level-up-alt me-2 text-primary"></i> ການອັບເກຣດ
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->is('semesters*') ? 'active-link' : 'text-dark' }}" href="{{ route('semesters.index') }}">
                            <i class="fas fa-calendar me-2 text-primary"></i> ພາກຮຽນ
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->is('terms*') ? 'active-link' : 'text-dark' }}" href="{{ route('terms.index') }}">
                            <i class="fas fa-clock me-2 text-primary"></i> ເທີມ
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->is('years*') ? 'active-link' : 'text-dark' }}" href="{{ route('years.index') }}">
                            <i class="fas fa-calendar-alt me-2 text-primary"></i> ສົກຮຽນ
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->is('credits*') ? 'active-link' : 'text-dark' }}" href="{{ route('credits.index') }}">
                            <i class="fas fa-credit-card me-2 text-primary"></i> ໜ່ວຍກິດ
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->is('tuitions*') ? 'active-link' : 'text-dark' }}" href="{{ route('tuitions.index') }}">
                            <i class="fas fa-dollar-sign me-2 text-primary"></i> ຄ່າຮຽນ
                        </a>
                    </li>
                    @endif
                    
                    <li class="nav-item mb-2">
                        <a class="nav-link text-dark" href="{{ route('logout') }}">
                            <i class="fas fa-sign-out-alt me-2 text-primary"></i> ອອກຈາກລະບົບ
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="col-md-10">
            <!-- Header/Navbar -->
            <div class="navbar p-3 border-bottom">
                <div>
                    <h1 class="mb-0">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div>
                    @yield('page-actions')
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="p-3">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>

<script>
// Add missing confirmDelete function for delete buttons
function confirmDelete(formId) {
    Swal.fire({
        title: 'ທ່ານແນ່ໃຈບໍ?',
        text: "ທ່ານບໍ່ສາມາດຍ້ອນກັບໄດ້!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ແມ່ນ, ລຶບເລີຍ!',
        cancelButtonText: 'ຍົກເລີກ'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

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

@stack('scripts')

</body>
</html>
