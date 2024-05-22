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
    <title>Data Pegawai | Peminjaman Kendaraan</title>
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
                    <a href="" class="nav-item side-item nav-link ps-4 py-3 d-flex align-items-center active">
                        <i class="fa-solid fa-users fa-xl me-2 text-primary w-15 d-inline-flex justify-content-center"></i>
                        Pegawai
                        <i class="fa-solid fa-caret-right ms-2"></i>
                    </a>
                    <a href="/kendaraan" class="nav-item side-item nav-link ps-4 py-3 d-flex align-items-center">
                        <i class="fa-solid fa-car fa-xl me-2 text-primary w-15 d-inline-flex justify-content-center"></i>
                        Kendaraan
                        <i class="fa-solid fa-caret-right ms-2"></i>
                    </a>
                    <a href="/peminjaman" class="nav-item side-item nav-link ps-4 py-3 d-flex align-items-center">
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
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="bg-light text-center rounded p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h6 class="mb-0">Data Pegawai</h6>
                                <a href="/tambah_pegawai" class="text-decoration-none"><button type="submit" class="btn btn-sm btn-primary">Tambah Pegawai</button></a>
                            </div>
                            <div class="table-responsive">
                                {{-- Table --}}
                                <table class="table-hover table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">NIP</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Kelompok</th>
                                            <th scope="col">Status</th>
                                            <th scope="col" colspan="2">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($datapegawai as $pegawai)
                                            <tr class="align-middle">
                                                <th>{{($datapegawai->currentPage()-1) * $datapegawai->perPage() + $loop->iteration}}</th>
                                                <td>{{ $pegawai->nip }}</td>
                                                <td>{{ $pegawai->nama }}</td>
                                                <td>{{ $pegawai->kelompok }}</td>
                                                <td>{{ $pegawai->status }}</td>
                                                <td><a href="/ubahpegawai/{{ $pegawai->id }}" class="btn btn-success">Edit</a></td>
                                                <td><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#Hapus{{ $pegawai->id }}">Hapus</button></td>
                                            </tr>
                                            {{-- Confirmation Modal --}}
                                            <div class="modal fade" id="Hapus{{ $pegawai->id }}" tabindex="-1" aria-labelledby="HapusLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="HapusLabel">Hapus Data</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah anda yakin ingin menghapus data ini?<br>
                                                            <b>{{ $pegawai->nip }} - {{ $pegawai->nama }}</b>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form action="/hapuspegawai/{{ $pegawai->id }}">
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
                                    </tbody>
                                </table>
                            </div>
                            {!! $datapegawai->links() !!}
                        </div>
                    </div>
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