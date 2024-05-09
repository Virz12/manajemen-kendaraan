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
    <title>Tambah Data Kendaraan | Peminjaman Kendaraan</title>
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
                    <a href="/dashboard_kendaraan" class="nav-item side-item nav-link ps-4 py-3 d-flex align-items-center">
                        <i class="fa-solid fa-chart-line fa-xl me-2 text-primary w-15 d-inline-flex justify-content-center"></i>
                        Dashboard
                        <i class="fa-solid fa-caret-right ms-2"></i>
                    </a>
                    <a href="/data_kendaraan" class="nav-item side-item nav-link ps-4 py-3 d-flex align-items-center active">
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
            {{-- Card Form --}}
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="bg-light text-center rounded p-4">
                            <div class="text-start mb-4">
                                <a href="/data_kendaraan" class="mb-0 text-decoration-none text-black"><i class="fa-solid fa-arrow-left me-2"></i>Kembali</a>
                            </div>
                            {{-- Form --}}
                            <form action="" method="POST" enctype="multipart/form-data" class="row g-3">
                                @csrf
                                <div class="col-md-6">
                                    <label for="jenis_kendaraan" class="form-label w-100 text-start">Jenis Kendaraan</label>
                                    <input type="text" value="{{ @old('jenis_kendaraan') }}" name="jenis_kendaraan" class="form-control" id="jenis_kendaraan" min="1" autocomplete="off">
                                </div>
                                <div class="col-md-6">
                                    <label for="tahun" class="form-label w-100 text-start">Tahun Kendaraan</label>
                                    <input type="number" value="{{ @old('tahun') }}" name="tahun" class="form-control" id="tahun">
                                </div>
                                <div class="col-12">
                                    <label for="nopol" class="form-label w-100 text-start">No Polisi</label>
                                    <input type="text" value="{{ @old('nopol') }}" name="nopol" class="form-control" id="nopol" autocomplete="off">
                                </div>
                                <div class="col-12">
                                    <label for="warna" class="form-label w-100 text-start">Warna Kendaraan</label>
                                    <input type="text" value="{{ @old('warna') }}" name="warna" class="form-control" id="warna" autocomplete="off">
                                </div>
                                <div class="col-md-6">
                                    <label for="kondisi" class="form-label w-100 text-start">Kondisi Kendaraan</label>
                                    <select id="kondisi" name="kondisi" class="form-select">
                                        <option value="baik" default selected>Baik</option>
                                        <option value="rusak">Rusak</option>
                                        <option value="perbaikan">Perbaikan</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label w-100 text-start">Status Kendaraan</label>
                                    <select id="status" name="status" class="form-select">
                                        <option value="tersedia" default selected>Tersedia</option>
                                        <option value="digunakan">Digunakan</option>
                                    </select>
                                </div>
                                <div class="col-md-12 w-100 text-start">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Alert --}}
            @if($errors->any())
                <div class="position-fixed bottom-0 end-0 p-3">
                    @foreach ($errors->all() as $item)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            {{ $item }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endforeach
                </div>
            @endif
        </main>
    </div>

    {{-- Javascript --}}
    <script src="{{ asset('js/dashboard.js') }}"></script>

    {{-- ICON --}}
    <script src="https://kit.fontawesome.com/e814145206.js" crossorigin="anonymous"></script>

</body>
</html>