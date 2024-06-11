<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['preventBackHistory','guest'])->group(function () {
    Route::get('/', function () {
        return redirect('/login');
    });
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'storelogin']);
});

Route::middleware(['preventBackHistory','auth'])->group(function () {
    Route::get('/home', function() {
        if (Auth::user()->kelompok == 'admin') {
            return redirect('/dashboard_admin');
        }elseif (Auth::user()->kelompok == 'pegawai') {
            return redirect('/homepage_pegawai');
        }elseif (Auth::user()->kelompok == 'kendaraan') {
            return redirect('/dashboard_kendaraan');
        }
    });
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware(['preventBackHistory','auth','userAccess:admin'])->group(function () {
    //Admin
    Route::get('/dashboard_admin', [RedirectController::class, 'admin'])->name('admin.dashboard');
    Route::get('/tambah_pegawai', [AdminController::class, 'createpegawai'])->name('pegawai.create');
    Route::post('/tambah_pegawai', [AdminController::class, 'storepegawai']);
    Route::get('/ubahpegawai/{pegawai:id}', [AdminController::class, 'editpegawai'])->name('pegawai.edit');
    Route::put('/ubahpegawai/{pegawai:id}', [AdminController::class, 'updatepegawai']);
    Route::get('/hapuspegawai/{pegawai:id}', [AdminController::class, 'deletepegawai'])->name('pegawai.delete');
    Route::get('/pegawai', [AdminController::class, 'pegawai']);
    Route::get('/kendaraan', [AdminController::class, 'kendaraan']);
    Route::get('/peminjaman', [AdminController::class, 'peminjaman']);
    Route::get('/arsip', [AdminController::class, 'arsip']);
});

Route::middleware(['preventBackHistory','auth','userAccess:pegawai'])->group(function () {
    //Pegawai
    Route::get('/homepage_pegawai', [RedirectController::class, 'pegawai'])->name('pegawai.homepage');
    Route::post('/homepage_pegawai', [PeminjamanController::class, 'storepeminjaman']);
    Route::post('edit_peminjaman/{peminjaman:id}', [PeminjamanController::class, 'editpeminjaman']);
});

Route::middleware(['preventBackHistory','auth','userAccess:kendaraan'])->group(function () {
    //Tim Kendaraan
    Route::get('/dashboard_kendaraan', [RedirectController::class, 'kendaraan'])->name('kendaraan.dashboard');
    Route::get('/tambah_kendaraan', [KendaraanController::class, 'createkendaraan'])->name('kendaraan.create');
    Route::post('/tambah_kendaraan', [KendaraanController::class, 'storekendaraan']);
    Route::get('/ubahkendaraan/{kendaraan:id}', [KendaraanController::class, 'editkendaraan'])->name('kendaraan.edit');
    Route::put('/ubahkendaraan/{kendaraan:id}', [KendaraanController::class, 'updatekendaraan']);
    Route::get('/hapuskendaraan/{kendaraan:id}', [KendaraanController::class, 'deletekendaraan'])->name('kendaraan.delete');
    Route::get('/verifikasi_peminjaman/{peminjaman:id}', [PeminjamanController::class, 'pageverifikasipeminjaman'])->name('peminjaman.edit');
    Route::post('/verifikasi_peminjaman/{peminjaman:id}', [PeminjamanController::class, 'verifikasipeminjaman']);
    Route::get('/selesai_peminjaman/{peminjaman:id}', [PeminjamanController::class, 'selesaipeminjaman']);
    Route::get('/data_kendaraan', [KendaraanController::class, 'kendaraan']);
    Route::get('/data_peminjaman', [KendaraanController::class, 'peminjaman']);
    Route::get('/data_arsip', [KendaraanController::class, 'arsip']);
});