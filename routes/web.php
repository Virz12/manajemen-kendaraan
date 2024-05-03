<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function() {
    Route::get('/home', function() {
        if (Auth::user()->kelompok == 'admin') {
            return redirect('/dashboard_admin');
        }elseif (Auth::user()->kelompok == 'pegawai') {
            return redirect('/homepage_pegawai');
        }elseif (Auth::user()->kelompok == 'kendaraan') {
            return redirect('/dashboard_kendaraan');
        }elseif (Auth::user()->kelompok == 'supir') {
            return redirect('/homepage_supir');
        }
    });
});

Route::middleware(['preventBackHistory','guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'storelogin']);
});

Route::middleware(['preventBackHistory','auth'])->group(function () {
    //Admin
    Route::get('/dashboard_admin', [RedirectController::class, 'admin'])->name('admin.dashboard')->middleware('userAccess:admin');
    Route::get('/tambah_pegawai', [AdminController::class, 'createpegawai'])->name('admin.createpegawai')->middleware('userAccess:admin');
    Route::post('/tambah_pegawai', [AdminController::class, 'storepegawai'])->middleware('userAccess:admin');
    Route::get('/ubahpegawai/{pegawai:id}', [AdminController::class, 'editpegawai'])->name('admin.editpegawai')->middleware('userAccess:admin');
    Route::put('/ubahpegawai/{pegawai:id}', [AdminController::class, 'updatepegawai'])->middleware('userAccess:admin');
    Route::get('/hapuspegawai/{pegawai:id}',[AdminController::class,'deletepegawai'])->name('admin.deletepegawai')->middleware('userAccess:admin');
    
    //Pegawai
    Route::get('/homepage_pegawai', [RedirectController::class, 'pegawai'])->name('pegawai.homepage')->middleware('userAccess:pegawai');

    //Tim Kendaraan
    Route::get('/dashboard_kendaraan', [RedirectController::class, 'kendaraan'])->name('kendaraan.homepage')->middleware('userAccess:kendaraan');

    //Supir
    Route::get('/homepage_supir', [RedirectController::class, 'supir'])->name('supir.homepage')->middleware('userAccess:supir');

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});
