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
        ];

        $request->validate([
            'jumlah' => 'required|numeric',
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date',
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
        $pegawai = pegawai::where('id',Auth::id())->first();
        $datapeminjam = peminjaman::findOrFail($id);
        $datakendaraan = kendaraan::where('status','tersedia')->get();
        $datasupir = pegawai::where('kelompok','supir')->get();

        return view('kendaraan.verifikasi_peminjaman')
                ->with('pegawai',$pegawai)
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

        foreach($request->nopol as $nopol)
        {
            foreach($request->id_supir as $supir)
            {
                $data[] = [
                    'id_peminjaman' => $id,
                    'id_pegawai' => Auth::id(),
                    'id_supir' => $supir,
                    'nopol' => $nopol,
                ];
            }
            kendaraan::where('nopol',$nopol)->update([
                'status' => 'digunakan',
            ]);
        }

        detail_peminjaman::insert($data);
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
