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
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            @if(Auth::user()->foto_profil == null)
                                <span class="d-lg-inline-flex">{{ Auth::user()->username }}</span>
                            @else
                                @if(File::exists(Auth::user()->foto_profil))
                                    <img class="rounded-circle me-lg-2" src="{{ asset(Auth::user()->foto_profil) }}" alt="Profile picture"
                                    style="width: 40px; height: 40px;">
                                    <span class=" d-lg-inline-flex">{{ Auth::user()->username }}</span>
                                @else
                                    <span class=" d-lg-inline-flex">{{ Auth::user()->username }}</span>
                                @endif
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-1 rounded-0 rounded-bottom m-0">
                            <a href="/logout" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <main>
                {{-- card peminjaman terbaru --}}
                <div class="container-fluid p-0 mb-4 mt-4 ">
                    <div class="shadow-lg bg-light text-center rounded p-4 w-70  m-auto h-70vh ">
                        <div class="d-md-flex align-items-center justify-content-between mb-4">
                            <h6 class="fs-3 mb-0  ">Peminjaman Terbaru</h6>
                            <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#formPengajuan"><i class="fa-solid fa-car me-1 car-icon" style="color: #000000;"></i>Ajukan Peminjaman</button>
                        </div>
                        @forelse ($data_terbaru as $datapbaru)
                            <div class="col-sm-4 m-auto">
                                <div class="card">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item ">NIP : {{$datapbaru->nip_peminjam}}</li>
                                        <li class="list-group-item "> {{$datapbaru->tanggal_awal}} <br>  {{$datapbaru->tanggal_akhir}}</li>
                                        <li class="list-group-item ">Supir : 
                                        @if ($datapbaru->supir == true)
                                            <i class="fa-regular fa-square-check text-success" ></i>
                                        @else
                                            -
                                        @endif</li>
                                        <li class="list-group-item ">Kendaraan :  <br>
                                            @if ($datapbaru->status == 'pengajuan')
                                                -
                                            @elseif ($datapbaru->status == 'diterima')
                                                @foreach($datapbaru->detail_peminjaman as $detailpeminjaman)
                                                    @foreach($detailpeminjaman->kendaraan as $kendaraan)
                                                        {{ $kendaraan->jenis_kendaraan }} - {{ $kendaraan->nopol }}<br>
                                                    @endforeach                                    
                                                @endforeach
                                            @elseif ($datapbaru->status == 'selesai')
                                                @foreach($datapbaru->detail_peminjaman as $detailpeminjaman)
                                                    @foreach($detailpeminjaman->kendaraan as $kendaraan)
                                                        {{ $kendaraan->jenis_kendaraan }} - {{ $kendaraan->nopol }}<br>
                                                    @endforeach                                    
                                                @endforeach
                                            @endif
                                        </li>
                                        <li class="list-group-item ">Jumlah Kendaraan : {{$datapbaru->jumlah}}</li>
                                        <li class="list-group-item fw-bold">Status : {{$datapbaru->status}}</li>
                                    </ul>
                                </div>
                            </div>
                        @empty
                            <h2>Data Kosong</h2>
                        @endforelse
                    </div>
                </div>
                    {{-- Table --}}
                    <div class="container-fluid pt-4 px-4 h-90vh">
                        <div class="shadow-lg bg-light text-center rounded p-4 ">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h6 class="fs-4 mb-0">Peminjaman</h6>
                                
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
                                            <th scope="col">Supir</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data_peminjaman as $datapeminjam)
                                        <tr>
                                            <td>{{($data_peminjaman->currentPage()-1) * $data_peminjaman->perPage() + $loop->iteration}}</td>
                                            <td>{{$datapeminjam->nip_peminjam}}</td>
                                            <td>{{$datapeminjam->tanggal_awal}}</td>
                                            <td>{{$datapeminjam->tanggal_akhir}}</td>
                                            <td>{{$datapeminjam->jumlah}}</td>
                                            <td>
                                                @if ($datapeminjam->supir == true)
                                                    <i class="fa-regular fa-square-check text-success" ></i>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{$datapeminjam->status}}</td>
                                        </tr> 
                                        @empty
                                        <h2>Data Kosong</h2>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {!! $data_peminjaman->links() !!}
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
                                            <input type="date" id="tanggal_awal" name="tanggal_awal" min="2000-01-01" class="form-control ">
                                        </div>
                                        <div class="col-sm-6 flex-column d-flex">
                                            <label for="tanggal_akhir" class="form-label">Tanggal Akhir </label>
                                            <input type="date" id="tanggal_akhir" name="tanggal_akhir" max="2100-01-01" class="form-control ">
                                        </div> 
                                    </div>
                                    <div class="row justify-content-between text-left mb-2">
                                        <div class="col-sm-6 flex-column d-flex ">
                                            <label for="jumlah" class="form-label">Jumlah Kendaraan</label>
                                            <input type="number" id="jumlah" name="jumlah" class="form-control " placeholder="masukkan angka" >
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
        <script src="https://kit.fontawesome.com/e814145206.js" crossorigin="anonymous"></script>
    </body>
</html>