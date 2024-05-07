<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
</head>
<body>
    <h1>{{ Auth::user()->kelompok }}</h1>
    <a href="/logout">Logout</a><br>
    <a href="/tambah_kendaraan">Tambah Kendaraan</a><br>

    <table>
        <tr>
            <th>Jenis Kendaraan</th>
            <th>Tahun Kendaraan</th>
            <th>Nomor Polisi</th>
            <th>Warna Kendaraan</th>
            <th>Kondisi Kendaraan</th>
            <th>Status Kendaraan</th>
            <th>Aksi</th>
        </tr>
        @forelse($datakendaraan as $datakendaraan)
            <tr>
                <td>{{ $datakendaraan->jenis_kendaraan }}</td>
                <td>{{ $datakendaraan->tahun }}</td>
                <td>{{ $datakendaraan->nopol }}</td>
                <td>{{ $datakendaraan->warna }}</td>
                <td>{{ $datakendaraan->kondisi }}</td>
                <td>{{ $datakendaraan->status }}</td>
                <td>
                    <form action="/ubahkendaraan/{{ $datakendaraan->id }}">
                        @csrf
                        <button type="submit">Ubah</button>
                    </form>
                    <form onsubmit="return confirm('Anda yakin ingin menghapus data ini ?')" action="/hapuskendaraan/{{ $datakendaraan->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <h2>Data Kosong</h2>
        @endforelse
    </table><br><br>
    <h2>Data Peminjaman</h2><br>
    <table>
        <tr>
            <th>NIP Peminjam</th>
            <th>Jumlah Kendaraan</th>
            <th>Tanggal Awal</th>
            <th>Tanggal Akhir</th>
            <th>Supir</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        @forelse($datapeminjaman as $datapeminjaman)
            <tr>
                <td>{{ $datapeminjaman->nip_peminjam }}</td>
                <td>{{ $datapeminjaman->jumlah }}</td>
                <td>{{ $datapeminjaman->tanggal_awal }}</td>
                <td>{{ $datapeminjaman->tanggal_akhir }}</td>
                <td>{{ $datapeminjaman->supir }}</td>
                <td>{{ $datapeminjaman->status }}</td>
                <td><a href="/verifikasi_peminjaman/{{ $datapeminjaman->id }}">Verifikasi</a><td>
            </tr>
        @empty
            <h2>Data Kosong</h2>
        @endforelse
    </table><br><br>
    <h2>Detail Peminjaman</h2><br>
    <table>
        <tr>
            <th>Mobil</th>
            <th>Tim Kendaraan</th>
            <th>Supir</th>
        </tr>
        @forelse ($datadetail_peminjaman as $datadetail_peminjaman)
            <tr>
                <td>{{ $datadetail_peminjaman->nopol }}</td>
                <td>{{ $datadetail_peminjaman->id_pegawai }}</td>
                <td>{{ $datadetail_peminjaman->id_supir }}</td>
            </tr>
        @empty
            <h2>Data Kosong</h2>
        @endforelse
    </table><br><br>
</body>
</html>