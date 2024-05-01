<?php

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
            return redirect('/homepage');
        }elseif (Auth::user()->kelompok == 'kendaraan') {
            return redirect('/dashboard_kendaraan');
        }elseif (Auth::user()->kelompok == 'supir') {
            return redirect('/homepage');
        }
    });
});

Route::middleware(['preventBackHistory','guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'storelogin']);
});

Route::middleware(['preventBackHistory','auth'])->group(function () {
    Route::get('/dashboard_admin', [RedirectController::class, 'admin'])->name('admin.dashboard')->middleware('userAccess:admin');
    Route::get('/homepage', [RedirectController::class, 'pegawai'])->name('pegawai.homepage')->middleware('userAccess:pegawai|supir');
    Route::get('/dashboard_kendaraan', [RedirectController::class, 'kendaraan'])->name('kendaraan.homepage')->middleware('userAccess:kendaraan');

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});
