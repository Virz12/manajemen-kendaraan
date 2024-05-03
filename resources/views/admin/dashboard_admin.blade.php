<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard Admin</title>
</head>
<body>
    <h1>{{ Auth::user()->kelompok }}</h1>
    <a href="/logout">Logout</a><br>
    <a href="/tambah_pegawai">Tambah Data Pegawai</a>

    <table>
        <tr>
            <th>NIP</th>
            <th>Nama</th>
            <th>Foto Profil</th>
            <th>Kelompok</th>
            <th>Username</th>
            <th>Aksi</th>
        </tr>
        @foreach ($datapegawai as $datapegawai)
            <tr>
                <td>{{ $datapegawai->nip }}</td>
                <td>{{ $datapegawai->nama }}</td>
                @if( $datapegawai->foto_profil == null)
                    <td>Tidak ada foto profil</td>
                @else
                    <td>{{ $datapegawai->foto_profil }}</td>
                @endif
                <td>{{ $datapegawai->kelompok }}</td>
                <td>{{ $datapegawai->username }}</td>
                <td>
                    <form onsubmit="return confirm('Anda yakin ingin menghapus data ini ?')" action="/hapuspegawai/{{ $datapegawai->id }}" method="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" value="">Hapus</button>
                    </form>
                    <form action="/ubahpegawai/{{ $datapegawai->id }}" method="GET">
                        @csrf
                        <button type="submit" value="">Ubah</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
</body>
</html>