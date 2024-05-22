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
    <title>Arsip Data Peminjaman | Peminjaman Kendaraan</title>
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
                    <form class="d-flex d-md-none ms-3 mb-3" action="" method="GET"> {{-- Form Sidebar --}}
                        <input class="form-control border-0" type="search" name="keyword" value="{{ $keyword }}" placeholder="Search">
                    </form>
                    <a href="/dashboard_kendaraan" class="nav-item side-item nav-link ps-4 py-3 d-flex align-items-center">
                        <i class="fa-solid fa-chart-line fa-xl me-2 text-primary w-15 d-inline-flex justify-content-center"></i>
                        Dashboard
                        <i class="fa-solid fa-caret-right ms-2"></i>
                    </a>
                    <a href="/data_kendaraan" class="nav-item side-item nav-link ps-4 py-3 d-flex align-items-center">
                        <i class="fa-solid fa-car fa-xl me-2 text-primary w-15 d-inline-flex justify-content-center"></i>
                        Kendaraan
                        <i class="fa-solid fa-caret-right ms-2"></i>
                    </a>
                    <a href="/data_peminjaman" class="nav-item side-item nav-link ps-4 py-3 d-flex align-items-center active">
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
                <form class="d-none d-md-flex ms-4" action="" method="GET"> {{-- Form Navbar --}}
                    <input class="form-control border-0" type="search" name="keyword" value="{{ $keyword }}" placeholder="Search">
                </form>
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            @if(Auth::user()->foto_profil == null)
                                <span class="d-none d-lg-inline-flex">{{ Auth::user()->username }}</span>
                            @else
                                @if(File::exists(Auth::user()->foto_profil))
                                    <img class="rounded-circle me-lg-2" src="{{ asset(Auth::user()->foto_profil) }}" alt="Profile picture"
                                    style="width: 40px; height: 40px;">
                                    <span class="d-none d-lg-inline-flex">{{ Auth::user()->username }}</span>
                                @else
                                    <span class="d-none d-lg-inline-flex">{{ Auth::user()->username }}</span>
                                @endif
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-1 rounded-0 rounded-bottom m-0">
                            <a href="/logout" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            {{-- Card Table --}}
            <div class="container-fluid pt-4 px-4 pb-4">
                <div class="text-start mb-4">
                    <a href="/data_peminjaman" class="mb-0 text-decoration-none text-black"><i class="fa-solid fa-arrow-left me-2"></i>Kembali</a>
                </div>
                <div class="row g-4 text-center">
                    @forelse($datapeminjaman as $peminjaman)
                    <div class="col-md-4">
                        <div class="card ">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">NIP : {{ $peminjaman->nip_peminjam }}</li>
                                <li class="list-group-item">{{ $peminjaman->tanggal_awal }} <br> {{ $peminjaman->tanggal_akhir }}</li>
                                <li class="list-group-item">Kendaraan : <br>
                                    @foreach($peminjaman->detail_peminjaman as $detailpeminjaman)
                                        @foreach($detailpeminjaman->kendaraan as $kendaraan)
                                            {{ $kendaraan->jenis_kendaraan }} - {{ $kendaraan->nopol }}<br>
                                        @endforeach                                    
                                    @endforeach
                                </li>
                                <li class="list-group-item">Nama Supir : <br>
                                    @foreach($peminjaman->detail_peminjaman as $detailpeminjaman)
                                        @foreach($detailpeminjaman->supir as $supir)
                                            {{ $supir->nama }} - {{ $supir->id }}<br>
                                        @endforeach                                    
                                    @endforeach
                                </li>
                                <li class="list-group-item ">Memverifikasi : <br>
                                    {{ $peminjaman->detail_peminjaman->first()->tim_kendaraan->nama }}
                                </li>
                                <li class="list-group-item fw-bold">Status : {{ $peminjaman->status }}</li>
                            </ul>
                        </div>
                    </div>
                    @empty
                        <h2 class="text-center py-5">Data Kosong</h2>
                    @endforelse
                </div>
                {!! $datapeminjaman->links() !!}
            </div>
            {{-- Toast --}}
            @if (session()->has('notification'))
            <div class="position-fixed bottom-0 end-0 p-3">
                <div class="alert alert-success" role="alert">
                    <i class="fa-solid fa-check me-2"></i>
                    {{ session('notification') }}
                    <button type="button" class="btn-close success" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
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