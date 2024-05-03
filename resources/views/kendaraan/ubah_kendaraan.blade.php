<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ubah Data kendaraan</title>
</head>
<body>
    @if($errors->any())
        <ul>
            @foreach ($errors->all() as $item)
                <li>{{ $item }}</li>
            @endforeach    
        </ul>
    @endif 

    <form action="" method="POST">
        @csrf
        @method('PUT')
        <label for="jenis_kendaraan">Jenis Kendaraan</label><br>
        <input type="text" name="jenis_kendaraan" value="{{ $datakendaraan->jenis_kendaraan }}" id="jenis_kendaraan"><br>
        <label for="tahun">Tahun Kendaraan</label><br>
        <input type="number" name="tahun" value="{{ $datakendaraan->tahun }}" id="tahun"><br>
        <label for="nopol">Nomor Polisi</label><br>
        <input type="text" name="nopol" value="{{ $datakendaraan->nopol }}" id="nopol"><br>
        <label for="warna">Warna Kendaraan</label><br>
        <input type="text" name="warna" value="{{ $datakendaraan->warna }}" id="warna"><br>
        <label for="kondisi">Kondisi Kendaraan :</label><br>
        <select id="kondisi" name="kondisi">
            <option value="{{ $datakendaraan->kondisi }}" selected hidden>{{ $datakendaraan->kondisi }}</option>
            <option value="baik">Baik</option>
            <option value="rusak">Rusak</option>
            <option value="perbaikan">Perbaikan</option>
        </select><br>
        <label for="status">Status Kendaraan :</label><br>
        <select id="status" name="status">
            <option value="{{ $datakendaraan->status }}" selected hidden>{{ $datakendaraan->status }}</option>
            <option value="tersedia">Tersedia</option>
            <option value="digunakan">Digunakan</option>
        </select><br>
        <input type="submit" value="Ubah Data">
    </form>
</body>
</html>