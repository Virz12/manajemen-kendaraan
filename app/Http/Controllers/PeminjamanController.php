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
        $messages = [
            'required' => 'Kolom :attribute belum terisi.',
            'alpha' => 'Kolom :attribute hanya boleh berisi huruf.',
            'alpha_dash' => 'Kolom :attribute hanya boleh berisi huruf, angka, (-), (_).',
            'alpha_num' => 'Kolom :attribute hanya boleh berisi huruf dan angka',
            'size' => 'Kolom :attribute tidak boleh lebih dari 20 karakter',
            'numeric' => 'Kolom :attribute hanya boleh berisi angka',
            'unique' => ':attribute sudah digunakan',
            'regex:/^[\pL\s]+$/u' => 'Kolom :attribute hanya boleh berisi huruf dan spasi.',
            'image' => 'File Harus Berupa Gambar.',
            'max:15' => 'Kolom :attribute maksimal berisi 15 karakter.',
            'max:2048' => 'Ukuran file maksimal 2MB.',
            'digits_between:1,20' => 'Kolom :attribute maksimal berisi angka 20 digit.',
            'date' => 'Kolom :attribute harus berupa tanggal',
            'after_or_equal' => 'Kolom :attribute harus berupa tanggal setelah atau sama dengan hari ini',
        ];

        $request->validate([
            'jumlah' => 'required|numeric',
            'tanggal_awal' => 'required|date|after_or_equal:today',
            'tanggal_akhir' => 'required|date|after_or_equal:today',
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
        $messages = [
            'nopol.required' => 'Data Kendaraan belum terisi.',
        ];

        $request->validate([
            'nopol' => 'required',
            'id_supir' => 'nullable',
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
                'status' => 'digunakan'
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
