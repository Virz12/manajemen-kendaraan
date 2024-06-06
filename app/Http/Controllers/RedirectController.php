<?php

namespace App\Http\Controllers;

use App\Models\detail_peminjaman;
use App\Models\kendaraan;
use App\Models\pegawai;
use App\Models\peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\Auth;

class RedirectController extends Controller
{
    function admin(Request $request)
    {        
        //Data - Pegawai
            $jumlah_pegawai = pegawai::all()->count();
            $jumlah_pegawai_aktif = pegawai::where('status','aktif')->count();
        //Data - Supir
            $jumlah_supir = pegawai::where('kelompok','supir')->count();
            $jumlah_supir_aktif = pegawai::where('kelompok','supir')->where('status','aktif')->count();
        //Data - Kendaraan
            $jumlah_kendaraan = kendaraan::where('status','tersedia')->where('kondisi','baik')->count();
            $jumlah_kendaraan_digunakan = kendaraan::where('status','digunakan')->count();
            $jumlah_kendaraan_tersedia = kendaraan::where('status','tersedia')->where('kondisi','baik')->count();
        //Data - Peminjaman
            $data_peminjaman = peminjaman::where('status','pengajuan')->orderBy('created_at','DESC')->paginate(5);
            $data_tahun_peminjaman = peminjaman::selectRaw('YEAR(tanggal_awal) as year')
                                                    ->groupBy('year')
                                                    ->pluck('year');
        if ($request->tahun) {
            $tahun = $request->tahun;
        } else {
            $tahun = Carbon::now()->year;
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
                ->with('jumlah_pegawai',$jumlah_pegawai)
                ->with('jumlah_pegawai_aktif',$jumlah_pegawai_aktif)
                ->with('jumlah_supir',$jumlah_supir)
                ->with('jumlah_supir_aktif',$jumlah_supir_aktif)
                ->with('jumlah_kendaraan',$jumlah_kendaraan)
                ->with('jumlah_kendaraan_digunakan',$jumlah_kendaraan_digunakan)
                ->with('jumlah_kendaraan_tersedia',$jumlah_kendaraan_tersedia)
                ->with('data_peminjaman',$data_peminjaman)
                ->with('data_tahun_peminjaman',$data_tahun_peminjaman)
                ->with('chart', $chart)
                ->with('tahun', $tahun);
    }

    function pegawai()
    {
        $data_peminjaman = peminjaman::where('nip_peminjam',Auth::user()->nip)
                                        ->orderByRaw("FIELD(status, 'pengajuan', 'diterima' ,'selesai')")
                                        ->orderBy('created_at','DESC')->paginate(6);
                                        
        $data_terbaru = peminjaman::where('nip_peminjam',Auth::user()->nip)->orderBy('created_at','DESC')->paginate(1);
        $jumlah_kendaraan = kendaraan::where('status','tersedia')->where('kondisi','baik')->count();
        
        return view('pegawai.homepage')
                ->with('data_peminjaman',$data_peminjaman)
                ->with('data_terbaru',$data_terbaru)
                ->with('jumlah_kendaraan',$jumlah_kendaraan);
    }

    function kendaraan(Request $request)
    {
        //Data - Kendaraan
            $data_kendaraan = kendaraan::all();
            $jumlah_kendaraan = kendaraan::where('status','tersedia')->where('kondisi','baik')->count();
            $jumlah_kendaraan_digunakan = kendaraan::where('status','digunakan')->count();
            $jumlah_kendaraan_tersedia = kendaraan::where('status','tersedia')->where('kondisi','baik')->count();
            $jumlah_kendaraan_rusak = kendaraan::where('kondisi','rusak')->count();
            $jumlah_kendaraan_perbaikan = kendaraan::where('kondisi','perbaikan')->count();
        //Data - Peminjaman
            $data_peminjaman = peminjaman::where('status','pengajuan')->orderBy('created_at','DESC')->paginate(5);
            $data_detail_peminjaman = detail_peminjaman::all();
            $data_tahun_peminjaman = peminjaman::selectRaw('YEAR(tanggal_awal) as year')
                                                    ->groupBy('year')
                                                    ->pluck('year');

        if ($request->tahun) {
            $tahun = $request->tahun;
        } else {
            $tahun = Carbon::now()->year;
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
                ->with('data_kendaraan',$data_kendaraan)
                ->with('jumlah_kendaraan',$jumlah_kendaraan)
                ->with('jumlah_kendaraan_digunakan',$jumlah_kendaraan_digunakan)
                ->with('jumlah_kendaraan_tersedia',$jumlah_kendaraan_tersedia)
                ->with('jumlah_kendaraan_rusak',$jumlah_kendaraan_rusak)
                ->with('jumlah_kendaraan_diperbaiki',$jumlah_kendaraan_perbaikan)
                ->with('data_peminjaman',$data_peminjaman)
                ->with('data_detail_peminjaman',$data_detail_peminjaman)
                ->with('data_tahun_peminjaman',$data_tahun_peminjaman)
                ->with('chart', $chart)
                ->with('tahun', $tahun);
    }
}
