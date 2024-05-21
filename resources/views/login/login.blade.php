<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- Bootstrap --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- Css --}}
    <link rel="stylesheet" href="{{asset('css/login.css')}}" >
    
    <title>Halaman Login</title>
</head>
<body>
    <div class="container-fluid ">
        <div class="row">
            <div class="col-sm-7 width-xxl px-0 d-none d-sm-block ">
                <img src="{{asset('img/bglog.jpg')}}"
                alt="Login image" class="w-100  vh-100" style="object-fit: cover; object-position: left;">
            </div>
            <div class="col-sm-5 bg-log " style="height: 100vh"> 
                <div class="mx-auto mt-3 pb-xxl-5" style="width: 140px;">
                    <img src="{{asset('img/logo.png')}}" class="logo img-fluid" >
                </div>
                <div class="d-flex align-items-center justify-content-center px-2 pb-2 mt-2">
                    <form action="" method="POST" style="width: 460px;" class="shadow-lg  mt-4 px-4 pt-4 card bg-white" style="border-radius: 1rem;">
                        @csrf
                            <h3 class="fw-semibold fs-1 pb-2 text-black " style="width:150px ">Masuk</h3>
                            <i class="fa-solid fa-arrow-right-long icon pt-xl-2 pt-md-2" style="font-size: 35px"></i>
                        <div class="form-floating mb-4" >
                            <input type="text" name="username" value="{{ @old('username') }}" id="username" class="form-control form-control-lg border-2 border-warning" placeholder="" autocomplete="off" >
                            <label class="form-label" for="username">Nama Pengguna</label>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="password" name="password" value="{{ @old('password') }}" id="floatingInput" class="form-control form-control-lg border-2 border-warning" placeholder="" autocomplete="off" >
                            <label class="form-label " for="floatingInput">Sandi</label>
                        </div>
                        <div class="pt-1 mb-5">
                            <button class="button shadow-sm btn w-100 fw-semibold" style="" type="submit">Masuk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Alert --}}
        @if($errors->any())
        <div class="position-fixed bottom-0 end-0 p-3">
            @foreach ($errors->all() as $item)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    {{ $item }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endforeach
        </div>
    @endif
    </div>
    <script src="https://kit.fontawesome.com/e814145206.js" crossorigin="anonymous"></script>
</body>
</html>