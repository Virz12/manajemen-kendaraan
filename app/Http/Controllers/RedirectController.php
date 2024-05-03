<?php

namespace App\Http\Controllers;

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
        return view('kendaraan.dashboard_kendaraan');
    }

    function supir()
    {
        return view('supir.homepage');
    }
}
