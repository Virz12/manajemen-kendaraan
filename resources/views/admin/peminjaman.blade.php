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
    <title>Data peminjaman | Peminjaman Kendaraan</title>
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
                    <a href="/dashboard_admin" class="nav-item side-item nav-link ps-4 py-3 d-flex align-items-center">
                        <i class="fa-solid fa-chart-line fa-xl me-2 text-primary w-15 d-inline-flex justify-content-center"></i>
                        Dashboard
                        <i class="fa-solid fa-caret-right ms-2"></i>
                    </a>
                    <a href="/pegawai" class="nav-item side-item nav-link ps-4 py-3 d-flex align-items-center">
                        <i class="fa-solid fa-users fa-xl me-2 text-primary w-15 d-inline-flex justify-content-center"></i>
                        Pegawai
                        <i class="fa-solid fa-caret-right ms-2"></i>
                    </a>
                    <a href="/kendaraan" class="nav-item side-item nav-link ps-4 py-3 d-flex align-items-center">
                        <i class="fa-solid fa-car fa-xl me-2 text-primary w-15 d-inline-flex justify-content-center"></i>
                        Kendaraan
                        <i class="fa-solid fa-caret-right ms-2"></i>
                    </a>
                    <a href="/peminjaman" class="nav-item side-item nav-link ps-4 py-3 d-flex align-items-center active">
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
                            <a href="/logout" class="dropdown-item"><i class="fa-solid fa-right-from-bracket"></i> Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            {{-- Card Table --}}
            <div class="container-fluid pt-4 px-4 pb-4">
                <div class="row g-2 pb-2">
                    <div class="col-md-3">
                        <a href="/arsip" class="btn btn-primary rounded">Arsip <i class="fa-solid fa-box-archive me-1"></i></a>
                    </div>
                </div>
                <div class="row g-4 text-center">
                    @forelse($datapeminjaman as $peminjaman)
                    <div class="col-md-4 ">
                        <div class="card ">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Peminjam : <br>
                                    {{ $peminjaman->pegawai->nama }} - (NIP:{{ $peminjaman->nip_peminjam }})
                                </li>
                                <li class="list-group-item">Periode Peminjaman : <br>
                                    {{ $peminjaman->tanggal_awal }} - {{ $peminjaman->tanggal_akhir }}
                                </li>
                                <li class="list-group-item">Kendaraan : <br>
                                    @if ($peminjaman->status == 'pengajuan')
                                        -
                                    @elseif ($peminjaman->status == 'diterima')
                                        @foreach($peminjaman->detail_peminjaman as $detailpeminjaman)
                                            @foreach($detailpeminjaman->kendaraan as $kendaraan)
                                                {{ $kendaraan->jenis_kendaraan }} - {{ $kendaraan->nopol }}<br>
                                            @endforeach                                    
                                        @endforeach
                                    @endif
                                </li>
                                <li class="list-group-item">Supir : <br>
                                    @if ($peminjaman->status == 'pengajuan')
                                        -
                                    @elseif ($peminjaman->status == 'diterima')
                                        @foreach($peminjaman->detail_peminjaman as $detailpeminjaman)
                                            @foreach($detailpeminjaman->supir as $supir)
                                                {{ $supir->nama }} - (NIP:{{ $supir->nip }})<br>
                                            @endforeach                                    
                                        @endforeach
                                    @endif
                                </li>
                                <li class="list-group-item ">Diverifikasi oleh : <br>
                                    @if ($peminjaman->status == 'pengajuan')
                                        -
                                    @elseif ($peminjaman->status == 'diterima')
                                        {{ $peminjaman->detail_peminjaman->first()->tim_kendaraan->nama }} (NIP:{{ $peminjaman->detail_peminjaman->first()->tim_kendaraan->nip }})
                                    @endif
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
        </main>
    </div>

    {{-- Javascript --}}
    <script src="{{ asset('js/dashboard.js') }}"></script>

    {{-- ICON --}}
    <script src="https://kit.fontawesome.com/e814145206.js" crossorigin="anonymous"></script>

</body>
</html>