<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ajukan Peminjaman</title>
</head>
<body>
    @if($errors->any())
        <ul>
            @foreach ($errors->all() as $item)
                <li>{{ $item }}</li>
            @endforeach    
        </ul>
    @endif 

    <form action="" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="jumlah">Jumlah Kendaraan (Maksimal 5 Unit)</label><br>
        <input type="number" id="jumlah" name="jumlah" min="1" max="5">
        <label for="tanggal_awal">Tanggal Awal Peminjaman</label><br>
        <input type="date" id="tanggal_awal" name="tanggal_awal" min="2000-01-01"><br>
        <label for="tanggal_akhir">Tanggal Akhir Peminjaman</label><br>
        <input type="date" id="tanggal_akhir" name="tanggal_akhir" max="2100-01-01"><br>
        <input type="checkbox" id="supir" name="supir" value="1">
        <label for="supir">Supir</label><br>
        <input type="submit" value="Ajukan">
    </form>
</body>
</html>