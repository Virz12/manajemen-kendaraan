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
</body>
</html>