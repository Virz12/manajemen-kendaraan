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
    <title>Data Kendaraan | Peminjaman Kendaraan</title>
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
            <div class="container-fluid pt-4 px-4">
                <div class="row g-2 pb-2">
                    <div class="col-md-3">
                        <a href="{{ route('kendaraan.create') }}" class="btn btn-primary rounded">Tambah Kendaraan <i class="fa-solid fa-car me-1 car-icon"></i></a>
                    </div>
                </div>
                <div class="row g-4">
                    @forelse($datakendaraan as $kendaraan)
                    <div class="col-sm-6 col-md-4 col-xl-3 col-xxl-2">
                        <div class="card">
                            <div class="ratio ratio-16x9">
                                <img src="{{ asset($kendaraan->foto_kendaraan) }}" class="card-img-top" alt="Foto Kendaraaan">
                            </div>
                            <ul class="list-group list-group-flush text-center">
                                <li class="list-group-item">Jenis Kendaraan : <br>
                                    {{ $kendaraan->jenis_kendaraan }}
                                </li>
                                <li class="list-group-item">Supir : <br>
                                    @if($kendaraan->supir == null)
                                    
                                    @else
                                        {{ $kendaraan->supir->nama }}
                                    @endif
                                </li>
                                <li class="list-group-item">Tahun Kendaraan : <br>
                                    {{ $kendaraan->tahun }}
                                </li>
                                <li class="list-group-item">Nomor Polisi : <br>
                                    {{ $kendaraan->nopol }}
                                </li>
                                <li class="list-group-item">Warna Kendaraan : <br>
                                    {{ $kendaraan->warna }}
                                </li>
                                <li class="list-group-item position-relative"> Kondisi : 
                                @if ($kendaraan->kondisi == 'baik')
                                    <i class="fa-solid fa-check fa-lg text-success tooltip-icon"></i>
                                    <span class="tooltip-text invisible bg-black text-white text-center p-1 position-absolute start-50 top-0 translate-middle rounded">Baik</span>
                                @elseif ($kendaraan->kondisi == 'rusak')
                                    <i class="fa-solid fa-xmark fa-lg text-danger tooltip-icon"></i>
                                    <span class="tooltip-text invisible bg-black text-white text-center p-1 position-absolute start-50 top-0 translate-middle rounded">Rusak</span>
                                @else
                                    <i class="fa-solid fa-triangle-exclamation fa-lg text-warning tooltip-icon"></i>
                                    <span class="tooltip-text invisible bg-black text-white text-center p-1 position-absolute start-50 top-0 translate-middle rounded">Perbaikan</span>
                                </li>
                                @endif
                                <li class="list-group-item position-relative"> Status : 
                                @if ($kendaraan->status == 'tersedia')
                                    <i class="fa-solid fa-car fa-lg text-success tooltip-icon"></i>
                                    <span class="tooltip-text invisible bg-black text-white text-center p-1 position-absolute start-50 top-0 translate-middle rounded">Tersedia</span>
                                @else
                                    <i class="fa-solid fa-car-on fa-lg text-secondary tooltip-icon"></i>
                                    <span class="tooltip-text invisible bg-black text-white text-center p-1 position-absolute start-50 top-0 translate-middle rounded">Digunakan</span>
                                @endif
                                </li>
                                <li class="list-group-item d-flex">
                                    <form action="{{ route('kendaraan.edit', ['kendaraan' => $kendaraan]) }}" class="w-50">
                                        @csrf
                                        <button class="btn btn-success w-100" type="submit">Ubah</button>
                                    </form>
                                    <button type="button" class="btn btn-danger ms-4 w-50" data-bs-toggle="modal" data-bs-target="#Hapus{{ $kendaraan->id }}">Hapus</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    {{-- Confirmation Modal --}}
                    <div class="modal fade" id="Hapus{{ $kendaraan->id }}" tabindex="-1" aria-labelledby="HapusLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="HapusLabel">Hapus Data</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah anda yakin ingin menghapus data ini?<br>
                                    <b>{{ $kendaraan->jenis_kendaraan }} - {{ $kendaraan->nopol }}</b>
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('kendaraan.delete', ['kendaraan' => $kendaraan]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                        <h2 class="text-center py-5">Data Kosong</h2>
                    @endforelse
                    {!! $datakendaraan->links() !!}
                </div>
            </div>
        </main>
    </div>
    {{-- Toast --}}
    @if (session()->has('notification'))
        <div class="position-fixed bottom-0 end-0 p-3 z-3">
            <div class="alert alert-success" role="alert">
                <i class="fa-solid fa-check me-2"></i>
                {{ session('notification') }}
                <button type="button" class="btn-close success" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    {{-- Javascript --}}
    <script src="{{ asset('js/dashboard.js') }}"></script>

    {{-- ICON --}}
    <script src="https://kit.fontawesome.com/e814145206.js" crossorigin="anonymous"></script>

</body>
</html>