<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- Bootstrap --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- Manual CSS --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <title>Dashboard Admin</title>
</head>
<body>  
    <div class="container-fluid p-0">
        {{-- Sidebar --}}
        <div class="sidebar pe-3">
            <nav class="navbar flex">
                <div class="logo mx-5 my-2">
                    <img src="{{ asset('img/hu.png') }}" class="img-fluid px-3 mb-4 rounded-circle" alt="Logo">
                </div>
                <div class="navbar-nav w-100 gap-2 fw-medium mt-7 mt-lg-0">
                    <a href="" class="nav-item side-item nav-link ps-4 active">Dashboard</a>
                    <a href="" class="nav-item side-item nav-link ps-4">Pegawai</a>
                    <a href="" class="nav-item side-item nav-link ps-4">Kendaraan</a>
                </div>
            </nav>
        </div>

        {{-- Header --}}
        <nav class="content">
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="" class="navbar-brand flex d-lg-none me-4">
                    <img src="{{ asset('img/hu.png') }}" class="w-40px rounded-circle" alt="Logo">
                </a>
                <a href="" class="sidebar-toggler flex-shrink-0">
                    <img src="{{ asset('img/hu.png') }}" class="img-fluid rounded-circle" alt="Hamburger menu">
                </a>
                <form class="d-none d-md-flex ms-4">
                    <input class="form-control border-0" type="search" placeholder="Search">
                </form>
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <span class="d-none d-lg-inline-flex">Notification</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Profile updated</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="" class="dropdown-item">
                                <h6 class="fw-normal mb-0">New user added</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Password changed</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="" class="dropdown-item text-center">See all notifications</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="{{ asset('img/hu.png') }}" alt="Profile picture"
                                style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex">Hu Tao</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="" class="dropdown-item">My Profile</a>
                            <a href="" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
        </nav>
    </div>

    {{-- Javascript --}}
    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>