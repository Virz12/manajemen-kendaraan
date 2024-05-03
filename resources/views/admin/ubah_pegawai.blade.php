<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ubah Data Pegawai</title>
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
        @method('PUT')
        <label for="nip">NIP Pegawai</label><br>
        <input type="text" name="nip" value="{{ $datapegawai->nip }}" id="nip"><br>
        <label for="nama">Nama Pegawai</label><br>
        <input type="text" name="nama" value="{{ $datapegawai->nama }}" id="nama"><br>
        <label for="foto_profil">Foto Pegawai *Opsional</label><br>
        <input type="file" name="foto_profil" value="" id="foto_profil"><br>
        <label for="kelompok">kelompok Pegawai:</label><br>
        <select id="kelompok" name="kelompok">
            <option value="{{ $datapegawai->kelompok }}" selected hidden>{{ $datapegawai->kelompok }}</option> 
            <option value="admin">Admin</option>
            <option value="pegawai">Pegawai</option>
            <option value="kendaraan">Tim Kendaraan</option>
            <option value="supir">Supir</option>
        </select><br>
        <label for="username">Nama Pengguna</label><br>
        <input type="text" name="username" value="{{ $datapegawai->username }}" id="username"><br>
        <label for="password">Sandi</label><br>
        <input type="password" name="password" value="" id="password"><br>
        <input type="submit" value="Ubah">
    </form>
</body>
</html>