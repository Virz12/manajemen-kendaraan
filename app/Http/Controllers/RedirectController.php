<?php

namespace App\Http\Controllers;

use App\Models\detail_peminjaman;
use App\Models\kendaraan;
use App\Models\pegawai;
use App\Models\peminjaman;
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
        $datapeminjaman = peminjaman::all();
        $datadetail_peminjaman = detail_peminjaman::all();

        return view('kendaraan.dashboard_kendaraan')->with('datakendaraan',$datakendaraan)->with('datapeminjaman',$datapeminjaman)->with('datadetail_peminjaman',$datadetail_peminjaman);
    }
}
