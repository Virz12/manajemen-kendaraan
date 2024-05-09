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
    <title>Dashboard | Kendaraan</title>
</head>
<body>
    <div class="container-fluid p-0">
        {{-- Sidebar --}}
        <div class="sidebar pe-3">
            <nav class="navbar d-flex">
                <div class="logo mx-5 my-2">
                    <img src="{{ asset('img/logo.png') }}" class="img-fluid px-3 mb-4 rounded-circle" alt="Logo">
                </div>
                <div class="navbar-nav w-100 gap-2 fw-medium mt-7 mt-lg-0">
                    <form class="d-flex d-md-none ms-3 mb-3"> {{-- Form Sidebar --}}
                        <input class="form-control border-0" type="search" placeholder="Search">
                    </form>
                    <a href="" class="nav-item side-item nav-link ps-4 py-3 d-flex align-items-center active">
                        <i class="fa-solid fa-chart-line fa-xl me-2 text-primary w-15 d-inline-flex justify-content-center"></i>
                        Dashboard
                        <i class="fa-solid fa-caret-right ms-2"></i>
                    </a>
                    <a href="/data_kendaraan" class="nav-item side-item nav-link ps-4 py-3 d-flex align-items-center">
                        <i class="fa-solid fa-car fa-xl me-2 text-primary w-15 d-inline-flex justify-content-center"></i>
                        Kendaraan
                        <i class="fa-solid fa-caret-right ms-2"></i>
                    </a>
                    <a href="/data_peminjaman" class="nav-item side-item nav-link ps-4 py-3 d-flex align-items-center">
                        <i class="fa-solid fa-car-tunnel fa-xl me-2 text-primary w-15 d-inline-flex justify-content-center"></i>
                        Peminjaman
                        <i class="fa-solid fa-caret-right ms-2"></i>
                    </a>
                </div>
            </nav>
        </div>
        {{-- Main --}}
        <main class="content">
            {{-- Header --}}
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-1">
                <a href="" class="navbar-brand d-flex d-lg-none me-4">
                    <img src="{{ asset('img/logo.png') }}" class="w-40px rounded-circle" alt="Logo">
                </a>
                <a href="" class="sidebar-toggler flex-shrink-0 text-decoration-none text-black">
                    <i class="fa-solid fa-bars-staggered"></i>
                </a>
                <form class="d-none d-md-flex ms-4"> {{-- Form Navbar --}}
                    <input class="form-control border-0" type="search" placeholder="Search">
                </form>
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="{{ asset('img/hu.png') }}" alt="Profile picture"
                                style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex">Hu Tao</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="/logout" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            {{-- Data --}}
            <div class="container-fluid text-center p-4">
                <div class="row g-4">
                    <div div class="col-sm-6 col-xxl-3">
                        <div class="bg-light p-4 d-flex flex-row align-items-center justify-content-between rounded">
                            <i class="fa-solid fa-car-on fa-3x text-primary w-25"></i>
                            <div class="">
                                <b class="text-start d-inline-block w-100">0</b>
                                <p class="mb-1">Kendaraan Dipakai</p>
                            </div>
                        </div>
                    </div>
                    <div div class="col-sm-6 col-xxl-3">
                        <div class="bg-light p-4 d-flex flex-row align-items-center justify-content-between rounded">
                            <i class="fa-solid fa-car-burst fa-3x text-primary w-25"></i>
                            <div class="">
                                <b class="text-start d-inline-block w-100">0</b>
                                <p class="mb-1">Rusak</p>
                            </div>
                        </div>
                    </div>
                    <div div class="col-sm-6 col-xxl-3">
                        <div class="bg-light p-4 d-flex flex-row align-items-center justify-content-between rounded">
                            <i class="fa-solid fa-car fa-3x text-primary w-25"></i>
                            <div class="">
                                <b class="text-start d-inline-block w-100">0</b>
                                <p class="mb-1">Kendaraan Tersisa</p>
                            </div>
                        </div>
                    </div>
                    <div div class="col-sm-6 col-xxl-3">
                        <div class="bg-light p-4 d-flex flex-row align-items-center justify-content-between rounded">
                            <i class="fa-solid fa-wrench fa-3x text-primary w-25"></i>
                            <div class="">
                                <b class="text-start d-inline-block w-100">0</b>
                                <p class="mb-1">Servis</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Chart & Table --}}
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light text-center rounded p-4">
                            <div class="text-start mb-4">
                                <h6 class="mb-0">Peminjaman Bulanan</h6>
                            </div>
                            <canvas id="">{{-- Chart JS --}}</canvas>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light text-center rounded p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h6 class="mb-0">Status Peminjaman Terbaru</h6>
                                <a href="/data_peminjaman" class="text-decoration-none">Show All</a>
                            </div>
                            <div class="table-responsive">
                                {{-- Table --}}
                                <table class="table-hover table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">NIP Peminjam</th>
                                            <th scope="col">Jumlah Kendaraan</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($datapeminjaman as $peminjaman)
                                        <tr>
                                            <th>{{($datapeminjaman->currentPage()-1) * $datapeminjaman->perPage() + $loop->iteration}}</th>
                                            <td>{{ $peminjaman->nip_peminjam }}</td>
                                            <td>{{ $peminjaman->jumlah }}</td>
                                            <td>
                                                @if ($peminjaman->status == 'pengajuan')
                                                    <a href="/verifikasi_peminjaman/{{ $peminjaman->id }}"  class="text-decoration-none"><button class="btn btn-secondary ms-4">Verifikasi</button></a>
                                                @else
                                                    <a href=""  class="text-decoration-none"><button class="btn btn-success ms-4">Selesai</button></a>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                            <h2>Data Kosong</h2>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    {{-- Javascript --}}
    <script src="{{ asset('js/dashboard.js') }}"></script>

    {{-- ICON --}}
    <script src="https://kit.fontawesome.com/e814145206.js" crossorigin="anonymous"></script>
</body>
</html>