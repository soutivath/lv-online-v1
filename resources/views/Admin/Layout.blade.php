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
                <img src="assets/img/pf.jpg" alt="" class=" mt-3 " style="width: 120px; border-radius:50%;">
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
                                {{-- <li><a class="dropdown-item {{ request()->get('view') == 'profile' ? 'active' : '' }}" href="{{ route('main', ['view' => 'profile']) }}"><i class="bi bi-person me-2"></i>ໂປຣໄຟລ໌</a></li> --}}
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
    {{-- contents --}}
    <div class="container-fluid" style="background-color:#e4e5f0;"">
        @yield('contents');
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
