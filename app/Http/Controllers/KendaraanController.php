<?php

namespace App\Http\Controllers;

use App\Models\kendaraan;
use App\Models\peminjaman;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
                ], 'LIKE', "%$keyword%")
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
            $datapeminjaman = peminjaman::whereNot('status',  '=', 'selesai')
                ->whereAny([
                    'nip_peminjam',
                    'jumlah',
                    'tanggal_awal',
                    'tanggal_akhir',
                    'status',
                ], 'like', "%$keyword%")
                ->orWhereHas('detail_peminjaman', function (Builder $query) use ($keyword) {
                    $query->where('nopol', 'like', "%$keyword%")
                        ->orWhere('id_peminjaman', 'like', "%$keyword%")
                        ->orWhere('id_pegawai', 'like', "%$keyword%")
                        ->orWhere('id_supir', 'like', "%$keyword%")
                        ->orWhereHas('kendaraan', function (Builder $query) use ($keyword) {
                            $query->where('jenis_kendaraan', 'like', "%$keyword%");
                        })
                        ->orWhereHas('supir', function (Builder $query) use ($keyword) {
                            $query->where('nama', 'like', "%$keyword%");
                        })
                        ->orWhereHas('tim_kendaraan', function (Builder $query) use ($keyword) {
                            $query->where('nama', 'like', "%$keyword%");
                        });
                })
                ->orderByRaw("FIELD(status, 'pengajuan', 'diterima')")
                ->orderBy('updated_at', 'desc')
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
            $datapeminjaman = peminjaman::where('status','selesai')
                ->whereAny([
                    'nip_peminjam',
                    'jumlah',
                    'tanggal_awal',
                    'tanggal_akhir',
                    'status',
                ], 'like', "%$keyword%")
                ->orWhereHas('detail_peminjaman', function (Builder $query) use ($keyword) {
                    $query->where('nopol', 'like', "%$keyword%")
                        ->orWhere('id_peminjaman', 'like', "%$keyword%")
                        ->orWhere('id_pegawai', 'like', "%$keyword%")
                        ->orWhere('id_supir', 'like', "%$keyword%")
                        ->orWhereHas('kendaraan', function (Builder $query) use ($keyword) {
                            $query->where('jenis_kendaraan', 'like', "%$keyword%");
                        })
                        ->orWhereHas('supir', function (Builder $query) use ($keyword) {
                            $query->where('nama', 'like', "%$keyword%");
                        })
                        ->orWhereHas('tim_kendaraan', function (Builder $query) use ($keyword) {
                            $query->where('nama', 'like', "%$keyword%");
                        });
                })
                ->orderBy('created_at', 'desc')
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
            'regex' => 'Kolom :attribute tidak valid.',
            'numeric' => 'Kolom :attribute hanya boleh berisi angka',
            'digits' => 'kolom :attribute tidak valid',
            'alpha_num' => 'Kolom :attribute hanya boleh berisi huruf dan angka',
            'unique' => ':attribute sudah digunakan',
            'jenis_kendaraan.max' => 'Kolom :attribute maksimal berisi 50 karakter.',
            'warna.max' => 'Kolom :attribute maksimal berisi 15 karakter.',
        ];

        $request->validate([
            'jenis_kendaraan' => 'required|max:50|regex:/^[a-z\d\-_\s]+$/i',
            'tahun' => 'required|numeric|digits:4',
            'nopol' => 'nullable|alpha_num|unique:kendaraan,nopol',
            'warna' => 'required|max:15|regex:/^[\pL\s]+$/u',
            'kondisi' => 'required|in:baik,rusak,perbaikan',
            'status' => 'required|in:tersedia,digunakan',
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
            'regex' => 'Kolom :attribute hanya boleh berisi huruf dan spasi.',
            'numeric' => 'Kolom :attribute hanya boleh berisi angka',
            'digits' => 'kolom :attribute tidak valid',
            'alpha_num' => 'Kolom :attribute hanya boleh berisi huruf dan angka',
            'jenis_kendaraan.max' => 'Kolom :attribute maksimal berisi 50 karakter.',
            'warna.max' => 'Kolom :attribute maksimal berisi 15 karakter.',
        ];

        $request->validate([
            'jenis_kendaraan' => 'required|max:50|regex:/^[\pL\s]+$/u',
            'tahun' => 'required|numeric|digits:4',
            'nopol' => 'nullable|alpha_num|',Rule::unique('kendaraan','nopol')->ignore($request->input('nopol')),
            'warna' => 'required|max:15|regex:/^[\pL\s]+$/u',
            'kondisi' => 'required|in:baik,rusak,perbaikan',
            'status' => 'required|in:tersedia,digunakan',
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
