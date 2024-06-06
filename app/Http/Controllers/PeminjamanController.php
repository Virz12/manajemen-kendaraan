<?php

namespace App\Http\Controllers;

use App\Models\detail_peminjaman;
use App\Models\kendaraan;
use App\Models\pegawai;
use App\Models\peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    function storepeminjaman(Request $request)
    {
        $jumlah_kendaraan = kendaraan::where('status','tersedia')->where('kondisi','baik')->count();

        $messages = [
            'required' => 'Kolom :attribute belum terisi.',
            'numeric' => 'Kolom :attribute hanya boleh berisi angka',
            'date' => 'Kolom :attribute harus berupa tanggal',
            'jumlah.min' => 'Jumlah kendaraan tidak dapat nol',
            'jumlah.max' => "Jumlah kendaraan maksimal adalah $jumlah_kendaraan",
            'tanggal_awal.after_or_equal' => 'Tanggal tidak valid',
            'tanggal_akhir.after_or_equal' => 'Tanggal tidak valid',            
        ];

        $request->validate([
            'jumlah' => "required|numeric|min:1|max:$jumlah_kendaraan",
            'tanggal_awal' => 'required|date|after_or_equal:today',
            'tanggal_akhir' => "required|date|after_or_equal:$request->tanggal_awal",
            'supir' => 'nullable',
        ],$messages);

        $data = [
            'nip_peminjam' => Auth::user()->nip,
            'jumlah' => $request->jumlah,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
            'supir' => $request->supir,
        ];

        peminjaman::create($data);

        return redirect('/homepage_pegawai')
                ->with('notification', 'Pengajuan Berhasil');
    }

    function editpeminjaman(string $id)
    {   
        $datapeminjam = peminjaman::findOrFail($id);
        $datakendaraan = kendaraan::where('status','tersedia')->where('kondisi','baik')->get();
        $datasupir = pegawai::where('kelompok','supir')->where('status','aktif')->get();

        return view('kendaraan.verifikasi_peminjaman')
                ->with('datakendaraan',$datakendaraan)
                ->with('datasupir',$datasupir)
                ->with('datapeminjam',$datapeminjam);
    }

    function updatepeminjaman(Request $request, string $id)
    {
        $jumlah_kendaraan = peminjaman::findOrFail($id)->count();

        $messages = [
            'nopol.required' => 'Data Kendaraan belum terisi.',
            'nopol.max' => 'Jumlah kendaraan melebihi permintaan',
            'id_supir' => 'Jumlah supir tidak sesuai',
        ];

        $request->validate([
            'nopol' => "required|max:$jumlah_kendaraan",
            'id_supir' => "nullable|size:$jumlah_kendaraan",
        ], $messages);

        $kendaraan = $request->input('nopol');
        $supir = $request->input('id_supir');
        $count = count($kendaraan);

        if($supir == null)
        {
            foreach($kendaraan as $data)
            {
                $item = new detail_peminjaman();
                $item->nopol = $data;
                $item->id_peminjaman = $id;
                $item->id_pegawai = Auth::id();
                $item->save();
            }
        }else {
            for ($i = 0; $i < $count; $i++) {
                $item = new detail_peminjaman();
                $item->nopol = $kendaraan[$i];
                $item->id_supir = $supir[$i];
                $item->id_peminjaman = $id;
                $item->id_pegawai = Auth::id();
                $item->save();
            }
        }
        
        foreach($kendaraan as $data)
        {
            kendaraan::where('nopol',$data)->update([
                'status' => 'digunakan',
            ]);
        }

        peminjaman::where('id',$id)->update([
            'status' => 'diterima',
        ]);
        
        return redirect('/data_peminjaman')
                ->with('notification', 'Berhasil Diverifikasi.');
    }

    function selesaipeminjaman(string $id)
    {
        $detail_peminjaman = detail_peminjaman::where('id_peminjaman',$id)->get();

        foreach($detail_peminjaman as $detailpeminjaman)
        {
            $kendaraan[] = $detailpeminjaman->nopol;
        }

        foreach($kendaraan as $nopol)
        {
            kendaraan::where('nopol',$nopol)->update([
                'status' => 'tersedia',
            ]);
        }

        peminjaman::where('id',$id)->update([
            'status' => 'selesai',
        ]);

        return redirect('/data_peminjaman');
    }
}
