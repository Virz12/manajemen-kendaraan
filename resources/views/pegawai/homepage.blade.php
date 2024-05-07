<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- Bootstrap --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- Manual CSS --}}
    <link rel="stylesheet" href="{{ asset('css/pegawai.css') }}">
    <title>Homepage</title>
</head>
    <body>
        <div class="container-fluid p-0">
            {{-- header --}}
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-2 py-1">
                    <img src="{{ asset('img/logo.png') }}" class="w-40px rounded-circle" alt="Logo">  
                <form class=" d-flex ms-4 "> {{-- Form Navbar --}}
                    <input class="form-control border-0" type="search" placeholder="Search">
                </form>
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="{{ asset('img/hu.png') }}" alt="Profile picture"
                                style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex">Pegawai</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            {{-- <a href="" class="dropdown-item">My Profile</a> --}}
                            <a href="{{route('logout')}}" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <main class="content">
        {{-- Table --}}
        <div class="container-fluid pt-4 px-4">
                    <div class="bg-light text-center rounded p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h6 class="mb-0">Status Peminjaman</h6>
                            <button class="btn btn-primary ms-4" data-bs-toggle="modal" data-bs-target="#formPengajuan"><i class="fa-solid fa-car me-1 car-icon" style="color: #000000;"></i>Ajukan Peminjaman</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table-hover table">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">NIP Peminjam</th>
                                        <th scope="col">Tanggal Awal</th>
                                        <th scope="col">Tanggal Akhir</th>
                                        <th scope="col">Jumlah Kendaraan</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($datapeminjaman as $datapeminjam)
                                    <tr>
                                        <td>{{$datapeminjam->id}}</td>
                                        <td>{{$datapeminjam->nip_peminjam}}</td>
                                        <td>{{$datapeminjam->tanggal_awal}}</td>
                                        <td>{{$datapeminjam->tanggal_akhir}}</td>
                                        <td>{{$datapeminjam->jumlah}}</td>
                                        <td>{{$datapeminjam->status}}</td>
                                    </tr> 
                                    @empty
                                    <h2>Empty data</h2>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {!! $datapeminjaman->links() !!}
                    </div>
        </div>
        {{-- Modal- popup form --}}
        <div class="modal fade" id="formPengajuan" tabindex="-1" aria-labelledby="formPengajuanLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content container-fluid p-0">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="formPengajuanLabel">Formulir Pengajuan Peminjaman</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" class="form-card px-4 pt-4 ">
                        @csrf
                        <div class="row justify-content-between text-left mb-2">
                            <div class="col-sm-6 flex-column d-flex">
                                <label for="tanggal_awal" class="form-label">Tanggal Awal </label>
                                <input type="date" id="tanggal-awal" name="tanggal_awal" min="2000-01-01" class="form-control ">
                            </div>
                            <div class="col-sm-6 flex-column d-flex">
                                <label for="tanggal_akhir" class="form-label">Tanggal Akhir </label>
                                <input type="date" id="tanggal_akhir" name="tanggal_akhir" max="2100-01-01" class="form-control ">
                            </div> 
                        </div>
                        <div class="row justify-content-between text-left mb-2">
                            <div class="col-sm-6 flex-column d-flex ">
                                <label for="jumlah" class="form-label">Jumlah Kendaraan</label>
                                <input type="number" id="jumlah" name="jumlah" min="1" max="5" class="form-control " placeholder="maks. 5 unit">
                            </div>
                            <div class="col-sm-6 flex-column  text-center mt-4">
                                <label for="supir" class="form-label">Supir</label>
                                <input type="checkbox" id="supir" name="supir" value="1" class="">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-car-on me-1 car-icon"></i>Ajukan</button>
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
        <script src="https://kit.fontawesome.com/e814145206.js" crossorigin="anonymous"></script>
    </body>
</html>