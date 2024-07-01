<?php

namespace App\Http\Controllers;

use App\Models\kendaraan;
use App\Models\pegawai;
use App\Models\peminjaman;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
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
        $data_supir = pegawai::where('kelompok','supir')->get();

        return view('kendaraan.tambah_kendaraan')
            ->with('data_supir',$data_supir);
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
            'foto_kendaraan.max' => 'Ukuran file maksimal 2MB',
            'id_supir.required' => 'Supir belum terisi',
            'id_supir.unique' => 'Supir sudah digunakan',
            'jenis_kendaraan.max' => 'Kolom :attribute maksimal berisi 50 karakter.',
            'warna.max' => 'Kolom :attribute maksimal berisi 15 karakter.',
        ];

        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->error('Data gagal ditambah.');

        $request->validate([
            'jenis_kendaraan' => 'required|max:50|regex:/^[a-z\d\-_\s]+$/i',
            'tahun' => 'required|numeric|digits:4',
            'nopol' => 'required|alpha_num|unique:kendaraan,nopol',
            'warna' => 'required|max:15|regex:/^[\pL\s]+$/u',
            'foto_kendaraan' => 'required|image|max:2048',
            'id_supir' => 'required|unique:kendaraan,id_supir',
            'kondisi' => 'required|in:baik,rusak,perbaikan',
            'status' => 'required|in:tersedia,digunakan',
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
            'foto_kendaraan' => $imagePath,
            'id_supir' => $request->input('id_supir'),
            'kondisi' => $request->input('kondisi'),
            'status' => $request->input('status'),
        ];

        kendaraan::create($data);

        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->success('Data berhasil ditambah.');

        return redirect('/data_kendaraan');
    }

    function editkendaraan(kendaraan $kendaraan)
    {
        $data_supir = pegawai::where('kelompok','supir')->get();

        return view('kendaraan.ubah_kendaraan')
                ->with('datakendaraan',$kendaraan)
                ->with('data_supir',$data_supir);
    }

    function updatekendaraan(Request $request, kendaraan $kendaraan)
    {
        $messages = [
            'required' => 'Kolom :attribute belum terisi.',
            'regex' => 'Kolom :attribute tidak valid.',
            'numeric' => 'Kolom :attribute hanya boleh berisi angka',
            'digits' => 'kolom :attribute tidak valid',
            'alpha_num' => 'Kolom :attribute hanya boleh berisi huruf dan angka',
            'unique' => ':attribute sudah digunakan',
            'image' => 'File harus berupa gambar',
            'foto_kendaraan.max' => 'Ukuran file maksimal 2MB',
            'id_supir.required' => 'Supir belum terisi',
            'id_supir.unique' => 'Supir sudah digunakan',
            'jenis_kendaraan.max' => 'Kolom :attribute maksimal berisi 50 karakter.',
            'warna.max' => 'Kolom :attribute maksimal berisi 15 karakter.',
        ];

        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->error('Data gagal diubah.');

        Validator::make($request->all(), [
            'jenis_kendaraan' => 'required|max:50|regex:/^[a-z\d\-_\s]+$/i',
            'tahun' => 'required|numeric|digits:4',
            'nopol' => ['required', 'alpha_num', Rule::unique('kendaraan','nopol')->ignore($kendaraan->id),],
            'warna' => 'required|max:15|regex:/^[\pL\s]+$/u',
            'foto_kendaraan' => 'image|max:2048',
            'id_supir' => ['required', Rule::unique('kendaraan','id_supir')->ignore($kendaraan->id),],
            'kondisi' => 'required|in:baik,rusak,perbaikan',
            'status' => 'required|in:tersedia,digunakan',
        ],$messages)->validate();

        if ($request->hasFile('foto_kendaraan')) {
            if (File::exists($kendaraan->foto_kendaraan))
            {
                File::delete($kendaraan->foto_kendaraan);
            }            
            $newImage = $request->file('foto_kendaraan');
            $imageName = time().'.'.$newImage->extension();
            $newImage->move(public_path('images'), $imageName);
            $imagePath = 'images/' . $imageName;

            $kendaraan->update([
                'foto_kendaraan' => $imagePath
            ]);
        }

        $data = [
            'jenis_kendaraan' => $request->input('jenis_kendaraan'),
            'tahun' => $request->input('tahun'),
            'nopol' => $request->input('nopol'),
            'warna' => $request->input('warna'),
            'id_supir' => $request->input('id_supir'),
            'kondisi' => $request->input('kondisi'),
            'status' => $request->input('status'),
        ];

        $kendaraan->update($data);

        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->success('Data berhasil diubah.');

        return redirect('/data_kendaraan');
    }

    function deletekendaraan(kendaraan $kendaraan)
    {
        kendaraan::destroy($kendaraan->id);

        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->success('Data berhasil dihapus.');

        return redirect('/data_kendaraan');
    }
}
