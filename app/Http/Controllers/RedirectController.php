<?php

namespace App\Http\Controllers;

use App\Models\kendaraan;
use App\Models\pegawai;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    function admin()
    {
        $datapegawai = pegawai::all();

        return view('admin.dashboard_admin')->with('datapegawai',$datapegawai);
    }

    function pegawai()
    {
        return view('pegawai.homepage');
    }

    function kendaraan()
    {
        $datakendaraan = kendaraan::all();

        return view('kendaraan.dashboard_kendaraan')->with('datakendaraan',$datakendaraan);
    }

    function supir()
    {
        return view('supir.homepage');
    }
}
