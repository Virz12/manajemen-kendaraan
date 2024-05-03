<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tambah Data kendaraan</title>
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
        <label for="jenis_kendaraan">Jenis Kendaraan</label><br>
        <input type="text" name="jenis_kendaraan" value="{{ @old('jenis_kendaraan') }}" id="jenis_kendaraan"><br>
        <label for="tahun">Tahun Kendaraan</label><br>
        <input type="number" name="tahun" value="{{ @old('tahun') }}" id="tahun"><br>
        <label for="nopol">Nomor Polisi</label><br>
        <input type="text" name="nopol" value="{{ @old('nopol') }}" id="nopol"><br>
        <label for="warna">Warna Kendaraan</label><br>
        <input type="text" name="warna" value="{{ @old('warna') }}" id="warna"><br>
        <label for="kondisi">Kondisi Kendaraan :</label><br>
        <select id="kondisi" name="kondisi">
          <option value="baik" default selected>Baik</option>
          <option value="rusak">Rusak</option>
          <option value="perbaikan">Perbaikan</option>
        </select><br>
        <label for="status">Status Kendaraan :</label><br>
        <select id="status" name="status">
          <option value="tersedia" default selected>Tersedia</option>
          <option value="digunakan">Digunakan</option>
        </select><br>
        <input type="submit" value="Tambah Data">
    </form>
</body>
</html>