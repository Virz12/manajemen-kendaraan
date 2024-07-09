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
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-1">
                <img src="{{ asset('img/logo.png') }}" class="w-40px rounded-circle" alt="Logo">  
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="dropdown me-3">
                        <div class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            @if($data_notification->contains('id_pegawai',Auth::id()))
                                <i class="fa-solid fa-bell fa-xl">
                                    <span class="position-absolute top-0 start-60 translate-middle p-2 bg-danger border border-light rounded-circle"></span>
                                    <span class="visually-hidden">New alerts</span>
                                </i>
                            @else
                                <i class="fa-solid fa-bell fa-xl"></i>
                            @endif
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end p-2">
                            @forelse(Auth::user()->notification->slice(0, 3) as $notification)
                                <li class="dropdown-item" data-bs-toggle="modal" role="button" data-bs-target="#lightbox{{ $notification->id }}">
                                    <h6 class="fw-normal mb-0">{{ $notification->notification }}</h6>
                                    <small>{{ $notification->created_at->setTimezone(new \DateTimeZone('Asia/Jakarta'))->format('Y-m-d H:i') }}</small>
                                </li>
                                <hr class="dropdown-divider">
                            @empty
                                <h6 class="fw-normal mb-0">Tidak ada notifikasi terbaru!</h6>
                            @endforelse
                        </ul>
                    </div>
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
                            <a href="/logout" class="dropdown-item"><i class="fa-solid fa-right-from-bracket"></i> Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <main>
                {{-- Card Peminjaman Terbaru --}}
                <div class="container-fluid p-0 mb-4 mt-4 ">
                    <div class="shadow-lg bg-light text-center rounded p-4 w-70  m-auto h-70vh ">
                        <div class="d-md-flex align-items-center justify-content-between mb-4">
                            <h6 class="fs-3 mb-0"><i class="fa-solid fa-circle-up"></i> Peminjaman Terbaru</h6>
                            <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#formPengajuan"><i class="fa-solid fa-car me-1 car-icon" style="color: #000000;"></i>Ajukan Peminjaman</button>
                        </div>
                        {{-- Modal Pengajuan Peminjaman --}}
                        <div class="modal fade" id="formPengajuan" tabindex="-1" aria-labelledby="formPengajuanLabel" aria-hidden="true">
                            <div class="modal-dialog">
                            <div class="modal-content container-fluid p-0">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="formPengajuanLabel">Pengajuan Peminjaman</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="" method="POST" class="form-card px-4 pt-4">
                                        @csrf
                                        <div class="row justify-content-between text-left mb-2">
                                            <div class="col-sm-6 flex-column d-flex">
                                                <label for="tanggal_awal" class="form-label">Tanggal Awal<span class="text-danger">*</span></label>
                                                <input type="date" id="tanggal_awal" name="pengajuan_tanggal_awal" min="{{ date("Y-m-d") }}" class="form-control @error('pengajuan_tanggal_awal') is-invalid @enderror">
                                                @error('pengajuan_tanggal_awal')
                                                <div class="text-danger"><small>{{ $message }}</small></div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6 flex-column d-flex">
                                                <label for="tanggal_akhir" class="form-label">Tanggal Akhir<span class="text-danger">*</span></label>
                                                <input type="date" id="tanggal_akhir" name="pengajuan_tanggal_akhir" min="{{ date("Y-m-d") }}" class="form-control @error('pengajuan_tanggal_akhir') is-invalid @enderror">
                                                @error('pengajuan_tanggal_akhir')
                                                <div class="text-danger"><small>{{ $message }}</small></div>
                                                @enderror
                                            </div> 
                                        </div>
                                        <div class="row justify-content-between text-left mb-2">
                                            <div class="col-sm-6 flex-column d-flex ">
                                                <label for="jumlah" class="form-label">Jumlah Kendaraan<span class="text-danger">*</span></label>
                                                <input type="number" id="jumlah" name="pengajuan_jumlah" min="1" max="{{ $jumlah_kendaraan }}" class="form-control @error('pengajuan_jumlah') is-invalid @enderror" placeholder="masukkan angka" >
                                                @error('pengajuan_jumlah')
                                                <div class="text-danger"><small>{{ $message }}</small></div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6 flex-column d-flex ">
                                                <label for="supir" class="form-label">Supir</label>
                                                <input type="number" id="supir" name="pengajuan_supir" min="1" max="" class="form-control @error('pengajuan_supir') is-invalid @enderror" placeholder="masukkan angka" >
                                                @error('pengajuan_supir')
                                                <div class="text-danger"><small>{{ $message }}</small></div>
                                                @enderror
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
                        @forelse ($data_terbaru as $datapbaru)
                            <div class="col-sm-4 m-auto">
                                <div class="card">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item ">NIP : {{$datapbaru->nip_peminjam}}</li>
                                        <li class="list-group-item "> {{$datapbaru->tanggal_awal}} <br>  {{$datapbaru->tanggal_akhir}}</li>
                                        <li class="list-group-item ">Supir : 
                                        @if ($datapbaru->supir == null)
                                            -
                                        @else
                                            {{ $datapbaru->supir }}
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
                                <h6 class="fs-4 mb-0"><i class="fa-solid fa-clock-rotate-left"></i> Peminjaman</h6>
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
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($data_peminjaman as $datapeminjam)
                                        <tr class="align-middle {{ $datapeminjam->status == 'selesai' ? 'table-secondary' : ''}}" data-bs-toggle="modal" role="button" data-bs-target="#lightbox{{ $datapeminjam->id }}">
                                            <td>{{($data_peminjaman->currentPage()-1) * $data_peminjaman->perPage() + $loop->iteration}}</td>
                                            <td>{{$datapeminjam->nip_peminjam}}</td>
                                            <td>{{$datapeminjam->tanggal_awal}}</td>
                                            <td>{{$datapeminjam->tanggal_akhir}}</td>
                                            <td>{{$datapeminjam->jumlah}}</td>
                                            <td>
                                                @if ($datapeminjam->supir == null)
                                                    -
                                                @else
                                                    {{ $datapeminjam->supir }}
                                                @endif
                                            </td>
                                            <td>{{$datapeminjam->status}}</td>
                                            @if($datapeminjam->status == 'pengajuan')
                                                <td><button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#formEditPengajuan{{ $datapeminjam->id }}">Edit</button></td>
                                            @else
                                                <td><button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#formEditPengajuan{{ $datapeminjam->id }}" disabled>Edit</button></td>
                                            @endif
                                        </tr>
                                        {{-- Modal Ubah Peminjaman --}}
                                        <div class="modal fade" id="formEditPengajuan{{ $datapeminjam->id }}" tabindex="-1" aria-labelledby="formEditPengajuanLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                            <div class="modal-content container-fluid p-0">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="formEditPengajuanLabel">Ubah Data Peminjaman</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="/edit_peminjaman/{{ $datapeminjam->id }}" method="POST" class="form-card px-4 pt-4 ">
                                                        @csrf
                                                        <div class="row justify-content-between text-left mb-2">
                                                            <div class="col-sm-6 flex-column d-flex">
                                                                <label for="tanggal_awal" class="form-label">Tanggal Awal<span class="text-danger">*</span></label>
                                                                <input type="date" id="tanggal_awal" name="ubah_tanggal_awal" value="{{ $datapeminjam->tanggal_awal }}" min="{{ date("Y-m-d") }}" class="form-control ">
                                                                @error('ubah_tanggal_awal')
                                                                    <div class="text-danger"><small>{{ $message }}</small></div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-sm-6 flex-column d-flex">
                                                                <label for="tanggal_akhir" class="form-label">Tanggal Akhir<span class="text-danger">*</span></label>
                                                                <input type="date" id="tanggal_akhir" name="ubah_tanggal_akhir" value="{{ $datapeminjam->tanggal_akhir }}" min="{{ date("Y-m-d") }}" class="form-control ">
                                                                @error('ubah_tanggal_akhir')
                                                                    <div class="text-danger"><small>{{ $message }}</small></div>
                                                                @enderror
                                                            </div> 
                                                        </div>
                                                        <div class="row justify-content-between text-left mb-2">
                                                            <div class="col-sm-6 flex-column d-flex ">
                                                                <label for="jumlah" class="form-label">Jumlah Kendaraan<span class="text-danger">*</span></label>
                                                                <input type="number" id="jumlah" name="ubah_jumlah" value="{{ $datapeminjam->jumlah }}" min="1" max="{{ $jumlah_kendaraan }}" class="form-control " placeholder="masukkan angka" >
                                                                @error('ubah_jumlah')
                                                                    <div class="text-danger"><small>{{ $message }}</small></div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-sm-6 flex-column d-flex">
                                                                <label for="supir" class="form-label">Supir</label>
                                                                <input type="number" id="supir" name="ubah_supir" value="{{ $datapeminjam->supir }}" min="1" max="" class="form-control @error('ubah_supir') is-invalid @enderror" placeholder="masukkan angka" >
                                                                @error('ubah_supir')
                                                                    <div class="text-danger"><small>{{ $message }}</small></div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-car-on me-1 car-icon"></i>Ganti</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="lightbox{{ $datapeminjam->id }}">
                                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                                <div class="modal-content">
                                                    <div class="card text-center">
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item ">NIP : {{ $datapeminjam->nip_peminjam }}</li>
                                                            <li class="list-group-item "> {{ $datapeminjam->tanggal_awal }} <br>  {{ $datapeminjam->tanggal_akhir }}</li>
                                                            <li class="list-group-item ">Supir : 
                                                                @if($datapeminjam->supir == null)
                                                                    -
                                                                @else
                                                                    {{ $datapeminjam->supir }}
                                                                @endif
                                                            </li>
                                                            <li class="list-group-item ">Kendaraan :  <br>
                                                                @if($datapeminjam->status == 'pengajuan')
                                                                    -
                                                                @elseif($datapeminjam->status == 'diterima')
                                                                    @foreach($datapeminjam->detail_peminjaman as $detailpeminjaman)
                                                                        @foreach($detailpeminjaman->kendaraan as $kendaraan)
                                                                            {{ $kendaraan->jenis_kendaraan }} - {{ $kendaraan->nopol }}<br>
                                                                        @endforeach                                    
                                                                    @endforeach
                                                                @elseif($datapeminjam->status == 'selesai')
                                                                    @foreach($datapeminjam->detail_peminjaman as $detailpeminjaman)
                                                                        @foreach($detailpeminjaman->kendaraan as $kendaraan)
                                                                            {{ $kendaraan->jenis_kendaraan }} - {{ $kendaraan->nopol }}<br>
                                                                        @endforeach                                    
                                                                    @endforeach
                                                                @endif
                                                            </li>
                                                            <li class="list-group-item ">Jumlah Kendaraan : {{ $datapeminjam->jumlah }}</li>
                                                            <li class="list-group-item fw-bold">Status : {{ $datapeminjam->status }}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <h2>Data Kosong</h2>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {!! $data_peminjaman->links() !!}
                        </div>
                    </div>
                    {{-- Notification Modal --}}
                    @foreach(Auth::user()->notification->slice(0, 3) as $notification)
                        <div class="modal fade" id="lightbox{{ $notification->id }}">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content">
                                    <div class="card text-center">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item ">NIP : {{ $notification->peminjaman->nip_peminjam }}</li>
                                            <li class="list-group-item "> {{ $notification->peminjaman->tanggal_awal }} <br>  {{ $notification->peminjaman->tanggal_akhir }}</li>
                                            <li class="list-group-item ">Supir : 
                                                @if($notification->peminjaman->supir == null)
                                                    -
                                                @else
                                                    {{ $notification->peminjaman->supir }}
                                                @endif
                                            </li>
                                            <li class="list-group-item ">Kendaraan :  <br>
                                                @if($notification->peminjaman->status == 'pengajuan')
                                                    -
                                                @elseif($notification->peminjaman->status == 'diterima')
                                                    @foreach($notification->peminjaman->detail_peminjaman as $detailpeminjaman)
                                                        @foreach($detailpeminjaman->kendaraan as $kendaraan)
                                                            {{ $kendaraan->jenis_kendaraan }} - {{ $kendaraan->nopol }}<br>
                                                        @endforeach                                    
                                                    @endforeach
                                                @elseif($notification->peminjaman->status == 'selesai')
                                                    @foreach($notification->peminjaman->detail_peminjaman as $detailpeminjaman)
                                                        @foreach($detailpeminjaman->kendaraan as $kendaraan)
                                                            {{ $kendaraan->jenis_kendaraan }} - {{ $kendaraan->nopol }}<br>
                                                        @endforeach                                    
                                                    @endforeach
                                                @endif
                                            </li>
                                            <li class="list-group-item ">Jumlah Kendaraan : {{ $notification->peminjaman->jumlah }}</li>
                                            <li class="list-group-item fw-bold">Status : {{ $notification->peminjaman->status }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    {{-- Toast --}}
                    @if(session()->has('notification'))
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
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                                Proses pengajuan tidak berhasil!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
            </main>
        </div>
        <script src="https://kit.fontawesome.com/e814145206.js" crossorigin="anonymous"></script>
    </body>
</html>