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
    <title>{{ config('app.name') }} | Admin | Tambah Data Pegawai</title>
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
                    <a href="{{ route('admin.dashboard') }}" class="nav-item side-item nav-link ps-4 py-3 d-flex align-items-center">
                        <i class="fa-solid fa-chart-line fa-xl me-2 text-primary w-15 d-inline-flex justify-content-center"></i>
                        Dashboard
                        <i class="fa-solid fa-caret-right ms-2"></i>
                    </a>
                    <a href="{{ route('admin.data.pegawai') }}" class="nav-item side-item nav-link ps-4 py-3 d-flex align-items-center active">
                        <i class="fa-solid fa-users fa-xl me-2 text-primary w-15 d-inline-flex justify-content-center"></i>
                        Pegawai
                        <i class="fa-solid fa-caret-right ms-2"></i>
                    </a>
                    <a href="{{ route('admin.data.kendaraan') }}" class="nav-item side-item nav-link ps-4 py-3 d-flex align-items-center">
                        <i class="fa-solid fa-car fa-xl me-2 text-primary w-15 d-inline-flex justify-content-center"></i>
                        Kendaraan
                        <i class="fa-solid fa-caret-right ms-2"></i>
                    </a>
                    <a href="{{ route('admin.data.peminjaman') }}" class="nav-item side-item nav-link ps-4 py-3 d-flex align-items-center">
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
                            <a href="{{ route('logout') }}" class="dropdown-item"><i class="fa-solid fa-right-from-bracket"></i> Log Out</a>
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
                                <a href="{{ route('admin.data.pegawai') }}" class="mb-0 text-decoration-none text-black"><i class="fa-solid fa-arrow-left me-2"></i>Kembali</a>
                            </div>
                            {{-- Form --}}
                            <form action="{{ route('admin.data.pegawai.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                                @csrf
                                <div class="col-md-6">
                                    <label for="nip" class="form-label w-100 text-start">NIP<span class="text-danger">*</span></label>
                                    <input type="number" value="{{ @old('nip') }}" name="nip" class="form-control @error('nip') is-invalid @enderror" id="nip" min="1" @required(true)>
                                    @error('nip')
                                        <div class="text-danger text-start"><small>{{ $message }}</small></div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="nama" class="form-label w-100 text-start">Nama<span class="text-danger">*</span></label>
                                    <input type="text" value="{{ @old('nama') }}" name="nama" class="form-control @error('nama') is-invalid @enderror" id="nama" autocomplete="off" @required(true)>
                                    @error('nama')
                                        <div class="text-danger text-start"><small>{{ $message }}</small></div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="username" class="form-label w-100 text-start">Nama Pengguna<span class="text-danger">*</span></label>
                                    <input type="text" value="{{ @old('username') }}" name="username" class="form-control @error('username') is-invalid @enderror"  id="username" autocomplete="off" @required(true)>
                                    @error('username')
                                        <div class="text-danger text-start"><small>{{ $message }}</small></div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="password" class="form-label w-100 text-start">Sandi<span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"  id="password" @required(true)>
                                    @error('password')
                                        <div class="text-danger text-start"><small>{{ $message }}</small></div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="Role" class="form-label w-100 text-start">Kelompok<span class="text-danger">*</span></label>
                                    <select id="Role" name="kelompok" class="form-select" @required(true)>
                                        <option value="pegawai" selected>Pegawai</option>
                                        <option value="admin" >Admin</option>
                                        <option value="supir" >Supir</option>
                                        <option value="kendaraan" >Kendaraan</option>
                                    </select>
                                    @error('kelompok')
                                        <div class="text-danger text-start"><small>{{ $message }}</small></div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="Status" class="form-label w-100 text-start">Status<span class="text-danger">*</span></label>
                                    <select id="Status" name="status" class="form-select @error('status') is-invalid @enderror" @required(true)>
                                        <option value="aktif" selected>Aktif</option>
                                        <option value="pensiun" >Pensiun</option>
                                        <option value="berhenti" >Berhenti</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger text-start"><small>{{ $message }}</small></div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="Profile" class="form-label w-100 text-start">Foto Profil</label>
                                    <input class="form-control @error('foto_profil') is-invalid @enderror" type="file" name="foto_profil" id="Profile">
                                    @error('foto_profil')
                                        <div class="text-danger text-start"><small>{{ $message }}</small></div>
                                    @enderror
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
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>
                        Proses Penambahan tidak berhasil!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
