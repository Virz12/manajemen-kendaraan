<?php

namespace App\Http\Controllers;

use App\Models\detail_peminjaman;
use App\Models\kendaraan;
use App\Models\pegawai;
use App\Models\peminjaman;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    function pegawai(Request $request)
    {
        $datapegawai = pegawai::orderBy('updated_at','DESC')->orderBy('id', 'DESC')->paginate(6);
        $keyword = $request->input('keyword');
        if ($keyword) {
            $datapegawai = DB::table('pegawai')
                ->orderBy('updated_at', 'DESC')
                ->orderBy('id', 'DESC')
                ->whereAny([
                    'nip',
                    'nama',
                    'kelompok',
                    'status',
                ], 'LIKE', "%$keyword%")
                ->paginate(6);
        }
        
        return view('admin.pegawai')
                ->with('datapegawai',$datapegawai)
                ->with('keyword',$keyword);
    }

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

        return view('admin.kendaraan')
                ->with('datakendaraan',$datakendaraan)
                ->with('keyword',$keyword);
    }

    function peminjaman(Request $request)
    {
        $datapeminjaman = peminjaman::orderByRaw("FIELD(status, 'pengajuan', 'diterima')")
                                        ->orderBy('updated_at','DESC')
                                        ->whereNot('status', '=', 'selesai')->paginate(6);
        $datadetail_peminjaman = detail_peminjaman::all();
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

        return view('admin.peminjaman')
                ->with('datapeminjaman',$datapeminjaman)
                ->with('datadetail_peminjaman',$datadetail_peminjaman)
                ->with('keyword',$keyword);
    }

    function arsip(Request $request)
    {
        $datapeminjaman = peminjaman::where('status','selesai')
                                        ->orderBy('created_at','DESC')->paginate(6);
        $datadetail_peminjaman = detail_peminjaman::all();
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

        return view('admin.arsip')
                ->with('datapeminjaman',$datapeminjaman)
                ->with('datadetail_peminjaman',$datadetail_peminjaman)
                ->with('keyword',$keyword);
    }
    
    function createpegawai()
    {
        return view('admin.tambah_pegawai');
    }

    function storepegawai(Request $request)
    {
        $messages = [
            'required' => 'Kolom :attribute belum terisi.',
            'numeric' => 'Kolom :attribute hanya boleh berisi angka',
            'unique' => ':attribute sudah digunakan',
            'regex:/^[\pL\s]+$/u' => 'Kolom :attribute hanya boleh berisi huruf dan spasi.',
            'image' => 'File harus berupa gambar.',
            'alpha_dash' => 'Kolom :attribute hanya boleh berisi huruf, angka, (-), (_).',
            'lowercase' => 'Kolom :attribute hnaya boleh berisi huruf kecil',
            'alpha_num' => 'Kolom :attribute hanya boleh berisi huruf dan angka',
            'nama.max' => 'Kolom :attribute maksimal berisi 50 karakter.',
            'foto_profil.max' => 'Ukuran file maksimal 2MB.',
            'username.max' => 'Kolom :attribute maksimal berisi 15 karakter.',
            'password.max' => 'Kolom :attribute maksimal berisi 15 karakter.',
        ];

        $request->validate([
            'nip' => 'required|numeric|digits_between:1,20|unique:pegawai,nip',
            'nama' => 'required|max:50|regex:/^[\pL\s]+$/u',
            'foto_profil' => 'nullable|image|max:2048',
            'kelompok' => 'required|in:pegawai,kendaraan,admin,supir',
            'username' => 'required|max:15|alpha_dash:ascii|lowercase|unique:pegawai,username',
            'password' => 'required|max:15|alpha_num:ascii',
        ],$messages);
 
        $data = [
            'nip' => $request->nip,
            'nama' => $request->nama,
            'kelompok' => $request->kelompok,
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ];

        if($pegawai = pegawai::create($data)) {
            if($request->hasFile('foto_profil')) {
                $image = $request->file('foto_profil');
                $imageName = $request->nama.'.'.$image->extension();
                $image->move(public_path('images'), $imageName);
                $imagePath = 'images/' . $imageName;

                $pegawai->update(['foto_profil' => $imagePath]);
            }
        }

        return redirect('/pegawai')
                ->with('notification', 'Data Berhasil Ditambah.');
    }

    function editpegawai(string $id)
    {
        $datapegawai = pegawai::findorFail($id);

        return view('admin.ubah_pegawai')
            ->with('datapegawai',$datapegawai);
    }

    function updatepegawai(Request $request, $id)
    {
        $messages = [
            'required' => 'Kolom :attribute belum terisi.',
            'digits_between' => 'Kolom :attribute maksimal berisi angka 20 digit.',
            'regex' => 'Kolom :attribute hanya boleh berisi huruf dan spasi.',
            'image' => 'File harus berupa gambar.',
            'alpha_dash' => 'Kolom :attribute hanya boleh berisi huruf, angka, (-), (_).',
            'lowercase' => 'Kolom :attribute hanya boleh berisi huruf kecil',
            'alpha_num' => 'Kolom :attribute hanya boleh berisi huruf dan angka',
            'nama.max' => 'Kolom :attribute maksimal berisi 50 karakter.',
            'foto_profil.max' => 'Ukuran file maksimal 2MB.',
            'username.max' => 'Kolom :attribute maksimal berisi 15 karakter.',
            'password.max' => 'Kolom :attribute maksimal berisi 15 karakter.',
        ];

        $request->validate([
            'nip' => 'required|numeric|digits_between:1,20|',Rule::unique('pegawai','nip')->ignore($request->input('nip')),
            'nama' => 'required|max:50|regex:/^[\pL\s]+$/u',
            'foto_profil' => 'nullable|image|max:2048',
            'kelompok' => 'required|in:pegawai,kendaraan,admin,supir',
            'username' => 'required|max:15|alpha_dash:ascii|lowercase|',Rule::unique('pegawai','username')->ignore($request->input('username')),
            'password' => 'required|max:15|alpha_num:ascii',
        ],$messages);

        $data = [
            'nip' => $request->nip,
            'nama' => $request->nama,
            'kelompok' => $request->kelompok,
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ];

        $pegawai = pegawai::findOrFail($id);

        if ($request->hasFile('foto_profil')) {
            if (File::exists($pegawai->foto_profil)) {
                File::delete($pegawai->foto_profil);
            }

            $newImage = $request->file('foto_profil');
            $imageName = $request->nama.'.'.$newImage->extension();
            $newImage->move(public_path('images'), $imageName);

            $pegawai->foto_profil = 'images/' . $imageName;
        }

        pegawai::where('id', $id)->update($data);
        $pegawai->save();
        
        return redirect('/pegawai')
                ->with('notification', 'Data Berhasil Diubah.');
    }

    function deletepegawai(pegawai $pegawai)
    {
        pegawai::destroy($pegawai->id);

        if (File::exists(public_path($pegawai->foto_profil))) {
            File::delete(public_path($pegawai->foto_profil));
        }

        return redirect('/pegawai')
                ->with('notification', 'Data Berhasil Dihapus.');
    }
}
