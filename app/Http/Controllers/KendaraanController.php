<?php

namespace App\Http\Controllers;

use App\Models\kendaraan;
use App\Models\peminjaman;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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
            $datapeminjaman = peminjaman::whereNot('status', '=', 'selesai')
                ->whereAny([
                    'nip_peminjam',
                    'tanggal_awal',
                    'tanggal_akhir',
                    'status',
                ], 'like', "%$keyword%")
                ->orWhereHas('detail_peminjaman', function (Builder $query) use ($keyword) {
                    $query->whereNot('status', '=', 'selesai')
                        ->whereAny([
                            'nopol',
                            'id_pegawai',
                            'id_supir',
                        ], 'like', "%$keyword%")
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
                    'tanggal_awal',
                    'tanggal_akhir',
                    'status',
                ], 'like', "%$keyword%")
                ->orWhereHas('detail_peminjaman', function (Builder $query) use ($keyword) {
                    $query->where('status','selesai')
                        ->whereAny([
                            'nopol',
                            'id_pegawai',
                            'id_supir',
                        ], 'like', "%$keyword%")
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
            'image' => 'File harus berupa gambar',
            'jenis_kendaraan.max' => 'Kolom :attribute maksimal berisi 50 karakter.',
            'warna.max' => 'Kolom :attribute maksimal berisi 15 karakter.',
            'foto_kendaraan.max' => 'Ukuran file maksimal 2MB',
        ];

        $request->validate([
            'jenis_kendaraan' => 'required|max:50|regex:/^[a-z\d\-_\s]+$/i',
            'tahun' => 'required|numeric|digits:4',
            'nopol' => 'required|alpha_num|unique:kendaraan,nopol',
            'warna' => 'required|max:15|regex:/^[\pL\s]+$/u',
            'kondisi' => 'required|in:baik,rusak,perbaikan',
            'status' => 'required|in:tersedia,digunakan',
            'foto_kendaraan' => 'required|image|max:2048',
        ],$messages);

        $image = $request->file('foto_kendaraan');
        $imageName = time().'.'.$image->extension();
        $image->move(public_path('images'), $imageName);
        $imagePath = 'images/' . $imageName;

        $data = [
            'jenis_kendaraan' => $request->input('jenis_kendaraan'),
            'tahun' => $request->input('tahun'),
            'nopol' => $request->input('nopol'),
            'warna' => $request->input('warna'),
            'kondisi' => $request->input('kondisi'),
            'status' => $request->input('status'),
            'foto_kendaraan' => $imagePath,
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
            'regex' => 'Kolom :attribute tidak valid.',
            'numeric' => 'Kolom :attribute hanya boleh berisi angka',
            'digits' => 'kolom :attribute tidak valid',
            'alpha_num' => 'Kolom :attribute hanya boleh berisi huruf dan angka',
            'unique' => ':attribute sudah digunakan',
            'image' => 'File harus berupa gambar',
            'jenis_kendaraan.max' => 'Kolom :attribute maksimal berisi 50 karakter.',
            'warna.max' => 'Kolom :attribute maksimal berisi 15 karakter.',
            'foto_kendaraan.max' => 'Ukuran file maksimal 2MB',
        ];

        $request->validate([
            'jenis_kendaraan' => 'required|max:50|regex:/^[a-z\d\-_\s]+$/i',
            'tahun' => 'required|numeric|digits:4',
            'nopol' => 'required|alpha_num|',Rule::unique('kendaraan','nopol')->ignore($request->input('nopol')),
            'warna' => 'required|max:15|regex:/^[\pL\s]+$/u',
            'kondisi' => 'required|in:baik,rusak,perbaikan',
            'status' => 'required|in:tersedia,digunakan',
            'foto_kendaraan' => 'image|max:2048',
        ],$messages);

        $kendaraan = kendaraan::findOrFail($id);

        if ($request->hasFile('foto_kendaraan')) {
            if (File::exists($kendaraan->foto_kendaraan))
            {
                File::delete($kendaraan->foto_kendaraan);
            }            
            $newImage = $request->file('foto_kendaraan');
            $imageName = time().'.'.$newImage->extension();
            $newImage->move(public_path('images'), $imageName);

            $kendaraan->foto_kendaraan = 'images/' . $imageName;
        }

        $data = [
            'jenis_kendaraan' => $request->input('jenis_kendaraan'),
            'tahun' => $request->input('tahun'),
            'nopol' => $request->input('nopol'),
            'warna' => $request->input('warna'),
            'kondisi' => $request->input('kondisi'),
            'status' => $request->input('status'),
        ];

        kendaraan::where('id', $id)->update($data);
        $kendaraan->save();

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
