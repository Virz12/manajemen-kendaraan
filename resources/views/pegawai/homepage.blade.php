<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Homepage</title>
</head>
<body>
    <h1>{{ Auth::user()->kelompok }}</h1>
    <a href="/logout">Logout</a><br><br>
    <a href="/tambah_peminjaman">Ajukan Peminjaman</a>
</body>
</html>