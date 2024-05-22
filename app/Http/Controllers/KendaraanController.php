<?php

namespace App\Http\Controllers;

use App\Models\kendaraan;
use App\Models\peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KendaraanController extends Controller
{
    function kendaraan(Request $request)
    {
        $datakendaraan = kendaraan::orderBy('updated_at','DESC')->orderBy('tahun', 'DESC')->paginate(6);
        $keyword = $request->input('keyword');
        if ($keyword) {
            $datakendaraan = DB::table('kendaraan')
                ->orderBy('updated_at', 'DESC')
                ->orderBy('tahun', 'DESC')
                ->whereAny([
                    'jenis_kendaraan',
                    'tahun',
                    'nopol',
                    'warna',
                    'kondisi',
                    'status',
                ], 'LIKE', $keyword)
                ->paginate(6);
        }

        return view('kendaraan.kendaraan')
                ->with('datakendaraan',$datakendaraan)
                ->with('keyword',$keyword);
    }

    function peminjaman(Request $request)
    {
        $datapeminjaman = peminjaman::orderByRaw("FIELD(status, 'pengajuan', 'diterima')")
                                        ->orderBy('updated_at','DESC')
                                        ->whereNot('status', '=', 'selesai')->paginate(6);
        $keyword = $request->input('keyword');
        if ($keyword) {
            $datapeminjaman = DB::table('peminjaman')
                ->orderByRaw("FIELD(status, 'pengajuan', 'diterima')")
                ->orderBy('updated_at', 'DESC')
                ->whereNot('status', '=', 'selesai')
                ->whereAny([
                    'nip_peminjam',
                    'jumlah',
                    'tanggal_awal',
                    'tanggal_akhir',
                    'status',
                ], 'LIKE', $keyword)
                ->paginate(6);
        }

        return view('kendaraan.peminjaman')
                ->with('datapeminjaman',$datapeminjaman)
                ->with('keyword',$keyword);
    }

    function arsip(Request $request)
    {   
        $datapeminjaman = peminjaman::where('status','selesai')
                                        ->orderBy('created_at','DESC')->paginate(6);
        $keyword = $request->input('keyword');
        if ($keyword) {
            $datapeminjaman = DB::table('peminjaman')
                ->orderBy('created_at', 'DESC')
                ->where('status','selesai')
                ->whereAny([
                    'nip_peminjam',
                    'jumlah',
                    'tanggal_awal',
                    'tanggal_akhir',
                    'status',
                ], 'LIKE', $keyword)
                ->paginate(6);
        }

        return view('kendaraan.arsip')
                ->with('datapeminjaman',$datapeminjaman)
                ->with('keyword',$keyword);
    }

    function createkendaraan()
    {
        return view('kendaraan.tambah_kendaraan');
    }

    function storekendaraan(Request $request)
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
        ];

        $request->validate([
            'jenis_kendaraan' => 'required|regex:/^[\pL\s]+$/u',
            'tahun' => 'required|numeric',
            'nopol' => 'nullable|alpha_num|unique:kendaraan,nopol',
            'warna' => 'required|alpha',
            'kondisi' => 'required|alpha',
            'status' => 'required|alpha',
        ],$messages);

        $data = [
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'tahun' => $request->tahun,
            'nopol' => $request->nopol,
            'warna' => $request->warna,
            'kondisi' => $request->kondisi,
            'status' => $request->status,
        ];

        kendaraan::create($data);

        return redirect('/data_kendaraan')
                ->with('notification', 'Data Berhasil Ditambah.');
    }

    function editkendaraan(string $id)
    {
        $datakendaraan = kendaraan::findOrFail($id);

        return view('kendaraan.ubah_kendaraan')
                ->with('datakendaraan',$datakendaraan);
    }

    function updatekendaraan(Request $request, $id)
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
        ];

        $request->validate([
            'jenis_kendaraan' => 'required|regex:/^[\pL\s]+$/u',
            'tahun' => 'required|numeric',
            'nopol' => 'nullable|alpha_num',
            'warna' => 'required|alpha',
            'kondisi' => 'required|alpha',
            'status' => 'required|alpha',
        ],$messages);
 
        $data = [
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'tahun' => $request->tahun,
            'nopol' => $request->nopol,
            'warna' => $request->warna,
            'kondisi' => $request->kondisi,
            'status' => $request->status,
        ];

        kendaraan::where('id', $id)->update($data);

        return redirect('/data_kendaraan')
                ->with('notification', 'Data Berhasil Diubah.');
    }

    function deletekendaraan(kendaraan $kendaraan)
    {
        kendaraan::destroy($kendaraan->id);

        return redirect('/data_kendaraan')
                ->with('notification', 'Data Berhasil Dihapus.');
    }
}
