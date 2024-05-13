<?php

namespace App\Http\Controllers;

use App\Models\detail_peminjaman;
use App\Models\kendaraan;
use App\Models\pegawai;
use App\Models\peminjaman;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\Auth;

class RedirectController extends Controller
{
    function admin(Request $request)
    {
        $pegawai = pegawai::where('id',Auth::id())->first();
        $datapeminjaman = peminjaman::orderBy('status','DESC')->paginate(5);
        $pegawai_aktif = pegawai::where('status','aktif')->get();
        $jumlahpegawai_aktif = $pegawai_aktif->count();
        $kendaraan_digunakan = kendaraan::where('status','digunakan');
        $jumlahkendaraan_digunakan = $kendaraan_digunakan->count();
        $kendaraan_tersedia = kendaraan::where('status','tersedia');
        $jumlahkendaraan_tersedia = $kendaraan_tersedia->count();
        $supir_aktif = pegawai::where('kelompok','supir')->where('status','aktif');
        $jumlahsupir_aktif = $supir_aktif->count();

        if ($request->tahun) {
            $tahun = $request->tahun;
        } else {
            $tahun = 2024;
        }

        $peminjaman = DB::table('peminjaman')
        ->selectRaw('YEAR(tanggal_awal) as year, MONTH(tanggal_awal) as month, COUNT(*) as count')
        ->whereYear('tanggal_awal', $tahun)
        ->groupBy('year', 'month')
        ->get()
        ->pluck('count', 'month')
        ->toArray();

        $bulan = [];
        $data = [];

        foreach ($peminjaman as $month => $count) {
            $date = DateTime::createFromFormat('!m', $month);
            $bulan[] = $date->format('F');
            $data[] = $count;
        }

        if (empty($bulan)) {
            $bulan = ['DATA KOSONG'];
            $data = [];
        }

        // Chart
        $chart = app()->chartjs
        ->name('lineChart')
        ->type('line')
        ->labels($bulan)
        ->datasets([
            [
                "label" => "Data Peminjaman",
                'backgroundColor' => "rgba(38, 185, 154, 0.31)",
                'borderColor' => "rgb(255, 197, 90)",
                "pointBorderColor" => "rgb(255, 197, 90)",
                "pointBackgroundColor" => "rgb(255, 197, 90)",
                "pointHoverBackgroundColor" => "#fff",
                "pointHoverBorderColor" => "rgba(220,220,220,1)",
                "data" => $data,
                "fill" => false,
            ]
        ])
        ->options([]);

        return view('admin.dashboard_admin')
                ->with('pegawai',$pegawai)
                ->with('jumlahpegawai_aktif',$jumlahpegawai_aktif)
                ->with('jumlahkendaraan_digunakan',$jumlahkendaraan_digunakan)
                ->with('jumlahkendaraan_tersedia',$jumlahkendaraan_tersedia)
                ->with('datapeminjaman',$datapeminjaman)
                ->with('jumlahsupir_aktif',$jumlahsupir_aktif)
                ->with('chart', $chart);
    }

    function pegawai()
    {
        $pegawai = pegawai::where('id',Auth::id())->first();
        $datapeminjaman = peminjaman::orderBy('updated_at','DESC')->paginate(6);
        $dataterbaru = peminjaman::orderBy('updated_at','DESC')->paginate(1);
        
        return view('pegawai.homepage')
                ->with('pegawai',$pegawai)
                ->with('datapeminjaman',$datapeminjaman)
                ->with('dataterbaru',$dataterbaru);
    }

    function kendaraan(Request $request)
    {
        $pegawai = pegawai::where('id',Auth::id())->first();
        $datakendaraan = kendaraan::all();
        $kendaraan_digunakan = kendaraan::where('status','digunakan');
        $jumlahkendaraan_digunakan = $kendaraan_digunakan->count();
        $kendaraan_tersedia = kendaraan::where('status','tersedia');
        $jumlahkendaraan_tersedia = $kendaraan_tersedia->count();
        $kendaraan_rusak = kendaraan::where('kondisi','rusak');
        $jumlahkendaraan_rusak = $kendaraan_rusak->count();
        $kendaraan_diperbaiki = kendaraan::where('kondisi','perbaikan');
        $jumlahkendaraan_diperbaiki = $kendaraan_diperbaiki->count();
        $datapeminjaman = peminjaman::orderBy('updated_at','DESC')->paginate(5);
        $datadetail_peminjaman = detail_peminjaman::all();

        if ($request->tahun) {
            $tahun = $request->tahun;
        } else {
            $tahun = 2024;
        }

        $peminjaman = DB::table('peminjaman')
        ->selectRaw('YEAR(tanggal_awal) as year, MONTH(tanggal_awal) as month, COUNT(*) as count')
        ->whereYear('tanggal_awal', $tahun)
        ->groupBy('year', 'month')
        ->get()
        ->pluck('count', 'month')
        ->toArray();

        $bulan = [];
        $data = [];

        foreach ($peminjaman as $month => $count) {
            $date = DateTime::createFromFormat('!m', $month);
            $bulan[] = $date->format('F');
            $data[] = $count;
        }

        if (empty($bulan)) {
            $bulan = ['DATA KOSONG'];
            $data = [];
        }

        // Chart
        $chart = app()->chartjs
        ->name('lineChart')
        ->type('line')
        ->labels($bulan)
        ->datasets([
            [
                "label" => "Data Peminjaman",
                'backgroundColor' => "rgba(38, 185, 154, 0.31)",
                'borderColor' => "rgb(255, 197, 90)",
                "pointBorderColor" => "rgb(255, 197, 90)",
                "pointBackgroundColor" => "rgb(255, 197, 90)",
                "pointHoverBackgroundColor" => "#fff",
                "pointHoverBorderColor" => "rgba(220,220,220,1)",
                "data" => $data,
                "fill" => false,
            ]
        ])
        ->options([]);


        return view('kendaraan.dashboard_kendaraan')
                ->with('pegawai',$pegawai)
                ->with('datakendaraan',$datakendaraan)
                ->with('jumlahkendaraan_digunakan',$jumlahkendaraan_digunakan)
                ->with('jumlahkendaraan_tersedia',$jumlahkendaraan_tersedia)
                ->with('jumlahkendaraan_rusak',$jumlahkendaraan_rusak)
                ->with('jumlahkendaraan_diperbaiki',$jumlahkendaraan_diperbaiki)
                ->with('datapeminjaman',$datapeminjaman)
                ->with('datadetail_peminjaman',$datadetail_peminjaman)
                ->with('chart', $chart);
    }
}
