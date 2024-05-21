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
    <title>Verifikasi Peminjaman | Peminjaman Kendaraan</title>
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
                    <a href="/dashboard_admin" class="nav-item side-item nav-link ps-4 py-3 d-flex align-items-center">
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
                <form class="row g-4" action="" method="POST">
                @csrf
                    <div class="col-md-12">
                        <div class="bg-light text-center rounded p-4">
                            <div class="text-start mb-4">
                                <a href="/data_peminjaman" class="mb-0 text-decoration-none text-black"><i class="fa-solid fa-arrow-left me-2"></i>Kembali</a>
                            </div>
                            <div class="text-start mb-4">
                                <h6 class="mb-0">Data Peminjam</h6>
                            </div>
                            <div class="table-responsive">
                                {{-- Table --}}
                                <table class="table-hover align-middle table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">NIP Peminjam</th>
                                            <th scope="col">Jumlah Kendaraan</th>
                                            <th scope="col">Tanggal Awal</th>
                                            <th scope="col">Tanggal Akhir</th>
                                            <th scope="col">Supir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="align-middle">
                                            <th>{{ $datapeminjam->id }}</th>
                                            <td>{{ $datapeminjam->nip_peminjam }}</td>
                                            <td>{{ $datapeminjam->jumlah }}</td>
                                            <td>{{ $datapeminjam->tanggal_awal }}</td>
                                            <td>{{ $datapeminjam->tanggal_akhir }}</td>
                                            <td>
                                                @if ($datapeminjam->supir == true)
                                                    <i class="fa-regular fa-square-check text-success" ></i>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="bg-light text-center rounded p-4">
                            <div class="text-start mb-4">
                                <h6 class="mb-0">Daftar Kendaraan</h6>
                            </div>
                            <div class="table-responsive">
                                {{-- Table --}}
                                <table class="table-hover align-middle table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Jenis Kendaraan</th>
                                            <th scope="col">Tahun Kendaraan</th>
                                            <th scope="col">Nomor Polisi</th>
                                            <th scope="col">Warna Kendaraan</th>
                                            <th scope="col">Kondisi Kendaraan</th>
                                            <th scope="col">Status Kendaraan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($datakendaraan as $kendaraan)
                                        <tr class="align-middle">
                                            <td><input type="checkbox" id="nopol" name="nopol[]" value="{{ $kendaraan->nopol }}"></td>
                                            <td>{{ $kendaraan->jenis_kendaraan }}</td>
                                            <td>{{ $kendaraan->tahun }}</td>
                                            <td>{{ $kendaraan->nopol }}</td>
                                            <td>{{ $kendaraan->warna }}</td>
                                            <td>{{ $kendaraan->kondisi }}</td>
                                            <td>{{ $kendaraan->status }}</td>
                                        </tr>
                                        @empty
                                            <h2 class="mb-5">Data Kosong</h2>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="bg-light text-center rounded p-4">
                            <div class="text-start mb-4">
                                <h6 class="mb-0">Daftar Supir</h6>
                            </div>
                            <div class="table-responsive">
                                {{-- Table --}}
                                <table class="table-hover align-middle table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">NIP</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Kelompok</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($datasupir as $supir)
                                        <tr class="align-middle">
                                            <td><input type="checkbox" id="id_supir" name="id_supir[]" value="{{ $supir->id }}"></td>
                                            <td>{{ $supir->nip }}</td>
                                            <td>{{ $supir->nama }}</td>
                                            <td>{{ $supir->status }}</td>
                                            <td>{{ $supir->kelompok }}</td>
                                        </tr>
                                        @empty
                                            <h2 class="mb-5">Data Kosong</h2>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 w-100 text-start mb-3">
                        <button type="submit" class="btn btn-primary">Verifikasi</button>
                    </div>
                </form>
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