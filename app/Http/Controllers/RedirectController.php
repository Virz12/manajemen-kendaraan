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
        $datapeminjaman = peminjaman::orderBy('status','DESC')->paginate(5);
        $pegawai_aktif = pegawai::where('status','aktif')->get();
        $jumlahpegawai_aktif = $pegawai_aktif->count();
        $kendaraan_digunakan = kendaraan::where('status','digunakan');
        $jumlahkendaraan_digunakan = $kendaraan_digunakan->count();
        $kendaraan_tersedia = kendaraan::where('status','tersedia');
        $jumlahkendaraan_tersedia = $kendaraan_tersedia->count();

        return view('admin.dashboard_admin')->with('jumlahpegawai_aktif',$jumlahpegawai_aktif)->with('jumlahkendaraan_digunakan',$jumlahkendaraan_digunakan)->with('jumlahkendaraan_tersedia',$jumlahkendaraan_tersedia)->with('datapeminjaman',$datapeminjaman);
    }

    function pegawai()
    {
        $datapeminjaman = peminjaman::orderBy('updated_at','DESC')->paginate(6);
        $dataterbaru = peminjaman::orderBy('updated_at','DESC')->paginate(1);
        
        return view('pegawai.homepage')->with('datapeminjaman',$datapeminjaman)->with('dataterbaru',$dataterbaru);
    }

    function kendaraan()
    {
        $datakendaraan = kendaraan::all();
        $datapeminjaman = peminjaman::orderBy('updated_at','DESC')->paginate(5);
        $datadetail_peminjaman = detail_peminjaman::all();

        return view('kendaraan.dashboard_kendaraan')->with('datakendaraan',$datakendaraan)->with('datapeminjaman',$datapeminjaman)->with('datadetail_peminjaman',$datadetail_peminjaman);
    }
}
