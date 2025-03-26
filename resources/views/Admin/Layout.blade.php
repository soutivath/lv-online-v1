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
    <title>@yield('title')</title>

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
                        <a class="nav-link active" aria-current="page" href="/">ໜ້າລັກ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/addnews">ລົງທະບຽນຮຽນ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/showdata">ອັບເກດ</a>
                    </li>
                    <li class="nav-item dropdown">
                        <button class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Admin login
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Header --}}
    <div class="container-fluid" style="background-color:#1c37e1;">
        <div class="row">
            <div class="col py-3 text-center" >
                <h1 class="text-light"> Online registration system of Laovieng College </h1>
            </div>
        </div>
    </div>
    {{-- contents --}}
    <div class="container-fluid" style="background-color:#e4e5f0;"">
        @yield('contents');
    </div>

    {{-- link bs_js5 --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
