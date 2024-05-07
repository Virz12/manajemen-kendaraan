<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verifikasi Peminjaman</title>
</head>
<body>
    <form action="" method="POST">
        @csrf
        <table>
            <tr>
                <th>Jenis Kendaraan</th>
                <th>Tahun Kendaraan</th>
                <th>Nomor Polisi</th>
                <th>Warna Kendaraan</th>
                <th>Kondisi Kendaraan</th>
                <th>Status Kendaraan</th>
            </tr>
                @forelse($datakendaraan as $datakendaraan)
                    <tr>
                        <td><input type="checkbox" id="nopol" name="nopol" value="{{ $datakendaraan->nopol }}"></td>
                        <td>{{ $datakendaraan->jenis_kendaraan }}</td>
                        <td>{{ $datakendaraan->tahun }}</td>
                        <td>{{ $datakendaraan->nopol }}</td>
                        <td>{{ $datakendaraan->warna }}</td>
                        <td>{{ $datakendaraan->kondisi }}</td>
                        <td>{{ $datakendaraan->status }}</td>
                    </tr>
                @empty
                    <h2>Data Kosong</h2>
                @endforelse
        </table><br><br>
        <table>
            <tr>
                <th>NIP</th>
                <th>Nama</th>
                <th>Status</th>
                <th>Kelompok</th>
            </tr>
                @forelse($datasupir as $datasupir)
                    <tr>
                        <td><input type="checkbox" id="id_supir" name="id_supir" value="{{ $datasupir->id }}"></td>
                        <td>{{ $datasupir->nip }}</td>
                        <td>{{ $datasupir->nama }}</td>
                        <td>{{ $datasupir->status }}</td>
                        <td>{{ $datasupir->kelompok }}</td>
                    </tr>
                @empty
                    <h2>Data Kosong</h2>
                @endforelse
        </table><br><br>
        <input type="submit" value="Verifikasi">
    </form>
</body>
</html>