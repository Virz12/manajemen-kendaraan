<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
<<<<<<< Updated upstream
=======
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{asset('css/login.css')}}" >
>>>>>>> Stashed changes
    <title>Halaman Login</title>
</head>
<body>
    <form action="" method="POST">
        @csrf
        <label for="username">Nama Pengguna</label><br>
        <input type="text" name="username" value="" id="username"><br>
        <label for="username">Sandi</label><br>
        <input type="password" name="password" value="" id="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>