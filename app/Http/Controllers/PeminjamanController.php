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
    function peminjaman()
    {
        return view('pegawai.tambah_peminjaman');
    }

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

        return redirect('/homepage_pegawai');
    }

    function editpeminjaman()
    {
        $datakendaraan = kendaraan::where('status','tersedia')->get();
        $datasupir = pegawai::where('kelompok','supir')->get();

        return view('kendaraan.verifikasi_peminjaman')->with('datakendaraan',$datakendaraan)->with('datasupir',$datasupir);
    }

    function updatepeminjaman(Request $request, string $id)
    {
        $data = [
            'nopol' => $request->nopol,
            'id_pegawai' => Auth::user()->id,
            'id_supir' => $request->id_supir,
        ];

        detail_peminjaman::create($data);
        peminjaman::where('id',$id)->update([
            'status' => 'diterima',
        ]);

        return redirect('/dashboard_kendaraan');
    }
}
