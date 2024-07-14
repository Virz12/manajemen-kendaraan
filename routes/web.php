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
        return redirect(route('login'));
    });
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'storelogin']);
});

Route::middleware(['preventBackHistory','auth'])->group(function () {
    Route::get('/home', function() {
        if (Auth::user()->kelompok == 'admin') {
            return redirect(route('admin.dashboard'));
        }elseif (Auth::user()->kelompok == 'pegawai') {
            return redirect(route('pegawai.homepage'));
        }elseif (Auth::user()->kelompok == 'kendaraan') {
            return redirect(route('kendaraan.dashboard'));
        }
    });
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::prefix('admin')->name('admin.')->middleware(['preventBackHistory','auth','userAccess:admin'])->group(function () {
    //Admin
    Route::get('/dashboard', [RedirectController::class, 'admin'])->name('dashboard');
    Route::get('/data/pegawai/tambah', [AdminController::class, 'createpegawai'])->name('data.pegawai.create');
    Route::post('/data/pegawai/simpan', [AdminController::class, 'storepegawai'])->name('data.pegawai.store');
    Route::get('/data/pegawai/ubah/{pegawai}', [AdminController::class, 'editpegawai'])->name('data.pegawai.edit');
    Route::put('/data/pegawai/simpan/{pegawai}', [AdminController::class, 'updatepegawai'])->name('data.pegawai.update');
    Route::get('/data/pegawai/hapus/{pegawai}', [AdminController::class, 'deletepegawai'])->name('data.pegawai.delete');
    Route::get('/data/pegawai', [AdminController::class, 'pegawai'])->name('data.pegawai');
    Route::get('/data/kendaraan', [AdminController::class, 'kendaraan'])->name('data.kendaraan');
    Route::get('/data/peminjaman', [AdminController::class, 'peminjaman'])->name('data.peminjaman');
    Route::get('/arsip', [AdminController::class, 'arsip'])->name('arsip');
});

Route::prefix('pegawai')->name('pegawai.')->middleware(['preventBackHistory','auth','userAccess:pegawai'])->group(function () {
    //Pegawai
    Route::get('/homepage', [RedirectController::class, 'pegawai'])->name('homepage');
    Route::post('/homepage/peminjaman/store', [PeminjamanController::class, 'storepeminjaman'])->name('peminjaman.store');
    Route::post('/homepage/peminjaman/edit/{peminjaman}', [PeminjamanController::class, 'editpeminjaman'])->name('peminjaman.edit');
});

Route::prefix('kendaraan')->name('kendaraan.')->middleware(['preventBackHistory','auth','userAccess:kendaraan'])->group(function () {
    //Tim Kendaraan
    Route::get('/dashboard', [RedirectController::class, 'kendaraan'])->name('dashboard');
    Route::get('/data/kendaraan/tambah', [KendaraanController::class, 'createkendaraan'])->name('data.kendaraan.create');
    Route::post('/data/kendaraan/simpan', [KendaraanController::class, 'storekendaraan'])->name('data.kendaraan.store');
    Route::get('/data/kendaraan/ubah/{kendaraan}', [KendaraanController::class, 'editkendaraan'])->name('data.kendaraan.edit');
    Route::put('/data/kendaraan/simpan/{kendaraan}', [KendaraanController::class, 'updatekendaraan'])->name('data.kendaraan.update');
    Route::get('/data/kendaraan/hapus/{kendaraan}', [KendaraanController::class, 'deletekendaraan'])->name('data.kendaraan.delete');
    Route::get('/data/peminjaman/verifikasi/{peminjaman}', [PeminjamanController::class, 'pageverifikasipeminjaman'])->name('data.peminjaman.verifikasi');
    Route::post('/data/peminjaman/verifikasi/simpan/{peminjaman}', [PeminjamanController::class, 'verifikasipeminjaman'])->name('data.peminjaman.update');
    Route::get('/data/peminjaman/selesai/{peminjaman}', [PeminjamanController::class, 'selesaipeminjaman'])->name('data.peminjaman.selesai');
    Route::get('/data/kendaraan', [KendaraanController::class, 'kendaraan'])->name('data.kendaraan');
    Route::get('/data/peminjaman', [KendaraanController::class, 'peminjaman'])->name('data.peminjaman');
    Route::get('/arsip', [KendaraanController::class, 'arsip'])->name('arsip');
});