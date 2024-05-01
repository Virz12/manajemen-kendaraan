<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RedirectController extends Controller
{
    function admin()
    {
        return view('admin.dashboard_admin');
    }

    function pegawai()
    {
        return view('pegawai.homepage');
    }

    function kendaraan()
    {
        return view('kendaraan.dashboard_kendaraan');
    }

    function supir()
    {
        return view('pegawai.homepage');
    }
}
