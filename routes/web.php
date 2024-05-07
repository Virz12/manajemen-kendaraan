<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PeminjamanController;
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
        }
    });
});

Route::middleware(['preventBackHistory','guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'storelogin']);
});
Route::middleware(['preventBackHistory','auth'])->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware(['preventBackHistory','auth','userAccess:admin'])->group(function () {
    //Admin
    Route::get('/dashboard_admin', [RedirectController::class, 'admin'])->name('admin.dashboard')->middleware('userAccess:admin');
    Route::get('/tambah_pegawai', [AdminController::class, 'createpegawai'])->name('pegawai.create')->middleware('userAccess:admin');
    Route::post('/tambah_pegawai', [AdminController::class, 'storepegawai'])->middleware('userAccess:admin');
    Route::get('/ubahpegawai/{pegawai:id}', [AdminController::class, 'editpegawai'])->name('pegawai.edit')->middleware('userAccess:admin');
    Route::put('/ubahpegawai/{pegawai:id}', [AdminController::class, 'updatepegawai'])->middleware('userAccess:admin');
    Route::get('/hapuspegawai/{pegawai:id}',[AdminController::class,'deletepegawai'])->name('pegawai.delete')->middleware('userAccess:admin');
});

Route::middleware(['preventBackHistory','auth','userAccess:pegawai'])->group(function () {
    //Pegawai
    Route::get('/homepage_pegawai', [RedirectController::class, 'pegawai'])->name('pegawai.homepage')->middleware('userAccess:pegawai');
    Route::get('/tambah_peminjaman', [PeminjamanController::class, 'peminjaman'])->name('peminjaman.add')->middleware('userAccess:pegawai');
    Route::post('/tambah_peminjaman', [PeminjamanController::class, 'storepeminjaman'])->middleware('userAccess:pegawai');
});

Route::middleware(['preventBackHistory','auth','userAccess:kendaraan'])->group(function () {
    //Tim Kendaraan
    Route::get('/dashboard_kendaraan', [RedirectController::class, 'kendaraan'])->name('kendaraan.dashboard')->middleware('userAccess:kendaraan');
    Route::get('/tambah_kendaraan', [KendaraanController::class, 'createkendaraan'])->name('kendaraan.create')->middleware('userAccess:kendaraan');
    Route::post('/tambah_kendaraan', [KendaraanController::class, 'storekendaraan'])->middleware('userAccess:kendaraan');
    Route::get('/ubahkendaraan/{kendaraan:id}', [KendaraanController::class, 'editkendaraan'])->name('kendaraan.edit')->middleware('userAccess:kendaraan');
    Route::put('/ubahkendaraan/{kendaraan:id}', [KendaraanController::class, 'updatekendaraan'])->middleware('userAccess:kendaraan');
    Route::get('/hapuskendaraan/{kendaraan:id}', [KendaraanController::class, 'deletekendaraan'])->name('kendaraan.delete')->middleware('userAccess:kendaraan');
    Route::get('/verifikasi_peminjaman/{peminjaman:id}', [PeminjamanController::class, 'editpeminjaman'])->name('peminjaman.edit')->middleware('userAccess:kendaraan');
    Route::post('/verifikasi_peminjaman/{peminjaman:id}', [PeminjamanController::class, 'updatepeminjaman'])->middleware('userAccess:kendaraan');
});