<?php

namespace App\Http\Controllers;

use App\Models\detail_peminjaman;
use App\Models\kendaraan;
use App\Models\pegawai;
use App\Models\peminjaman;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    function pegawai(Request $request)
    {
        $datapegawai = pegawai::orderBy('updated_at','DESC')->orderBy('id', 'DESC')->paginate(6);
        $keyword = $request->input('keyword');
        if ($keyword) {
            $datapegawai = 
            pegawai::whereAny([
                'nip',
                'nama',
                'kelompok',
                'status',
            ], 'LIKE', "%$keyword%")
                ->orderBy('updated_at', 'DESC')
                ->orderBy('id', 'DESC')
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
            $datakendaraan = 
            kendaraan::whereAny([
                'jenis_kendaraan',
                'tahun',
                'nopol',
                'warna',
                'kondisi',
                'status',
            ], 'LIKE', "%$keyword%")
            ->orWhereHas('supir', function (Builder $query) use ($keyword) {
                $query->where('nama', 'like', "%$keyword%");
            })
                ->orderBy('updated_at', 'DESC')
                ->orderBy('tahun', 'DESC')
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
                ], 'like', "$keyword%")
                ->orWhereHas('detail_peminjaman', function (Builder $query) use ($keyword) {
                    $query->whereNot('status', '=', 'selesai')
                        ->whereAny([
                            'nopol',
                            'id_pegawai',
                            'id_supir',
                        ], 'like', "$keyword%")
                        ->orWhereHas('kendaraan', function (Builder $query) use ($keyword) {
                            $query->where('jenis_kendaraan', 'like', "$keyword%");
                        })
                        ->orWhereHas('supir', function (Builder $query) use ($keyword) {
                            $query->where('nama', 'like', "$keyword%");
                        })
                        ->orWhereHas('tim_kendaraan', function (Builder $query) use ($keyword) {
                            $query->where('nama', 'like', "$keyword%");
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
                ], 'like', "$keyword%")
                ->orWhereHas('detail_peminjaman', function (Builder $query) use ($keyword) {
                    $query->where('status','selesai')
                        ->whereAny([
                            'nopol',
                            'id_pegawai',
                            'id_supir',
                        ], 'like', "$keyword%")
                        ->orWhereHas('kendaraan', function (Builder $query) use ($keyword) {
                            $query->where('jenis_kendaraan', 'like', "$keyword%");
                        })
                        ->orWhereHas('supir', function (Builder $query) use ($keyword) {
                            $query->where('nama', 'like', "$keyword%");
                        })
                        ->orWhereHas('tim_kendaraan', function (Builder $query) use ($keyword) {
                            $query->where('nama', 'like', "$keyword%");
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
            'username.max' => 'Kolom username maksimal berisi 15 karakter.',
            'password.min' => 'Kolom password minimal berisi 8 karakter.',
            'password.max' => 'Kolom password maksimal berisi 50 karakter.',
            'password.regex' => 'Kolom password minimal berisi salah satu karakter (!@#$%^&*()-=¡£_+`~.,<>/?;:"|[]{})',
        ];

        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->error('<b>Error!</b><br>Data pegawai gagal ditambah.');

        $request->validate([
            'nip' => 'required|numeric|digits_between:1,20|unique:pegawai,nip',
            'nama' => 'required|max:50|regex:/^[\pL\s]+$/u',
            'foto_profil' => 'nullable|image|max:2048',
            'status' => 'required|in:aktif,pensiun,berhenti',
            'kelompok' => 'required|in:pegawai,kendaraan,admin,supir',
            'username' => 'required|max:15|alpha_dash:ascii|lowercase|unique:pegawai,username',
            'password' => ['required', 'min:8', 'max:50', 'regex:/^.*(?!.*\s)(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\!\@\#\$\%\^\&\*\(\)\-\=\¡\£\_\+\`\~\.\,\<\>\/\?\;\:\'\"\\\|\[\]\{\}]).*$/',],
        ],$messages);
 
        $data = [
            'nip' => $request->input('nip'),
            'nama' => $request->input('nama'),
            'status' => $request->input('status'),
            'kelompok' => $request->input('kelompok'),
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
        ];

        if($pegawai = pegawai::create($data)) {
            if($request->hasFile('foto_profil')) {
                $image = $request->file('foto_profil');
                $imageName = time().'.'.$image->extension();
                $image->move(public_path('images'), $imageName);
                $imagePath = 'images/' . $imageName;

                $pegawai->update(['foto_profil' => $imagePath]);
            }
        }

        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->success('<b>Berhasil!</b><br>Data pegawai sudah ditambah.');

        return redirect(route('admin.data.pegawai'));
    }

    function editpegawai(pegawai $pegawai)
    {
        return view('admin.ubah_pegawai')
            ->with('pegawai',$pegawai);
    }

    function updatepegawai(Request $request, pegawai $pegawai)
    {
        $messages = [
            'required' => 'Kolom :attribute belum terisi.',
            'digits_between' => 'Kolom :attribute maksimal berisi angka 20 digit.',
            'regex' => 'Kolom :attribute hanya boleh berisi huruf dan spasi.',
            'image' => 'File harus berupa gambar.',
            'alpha_dash' => 'Kolom :attribute hanya boleh berisi huruf, angka, (-), (_).',
            'lowercase' => 'Kolom :attribute hanya boleh berisi huruf kecil',
            'alpha_num' => 'Kolom :attribute hanya boleh berisi huruf dan angka',
            'nip.unique' => 'NIP telah digunakan.',
            'nama.max' => 'Kolom :attribute maksimal berisi 50 karakter.',
            'foto_profil.max' => 'Ukuran file maksimal 2MB.',
            'username.max' => 'Kolom username maksimal berisi 15 karakter.',
            'password.min' => 'Kolom password minimal berisi 8 karakter.',
            'password.max' => 'Kolom password maksimal berisi 50 karakter.',
            'password.regex' => 'Kolom password minimal berisi salah satu karakter (!@#$%^&*()-=¡£_+`~.,<>/?;:"|[]{})',
        ];

        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->error('<b>Error!</b><br>Data pegawai gagal diperbarui.');

        Validator::make($request->all(), [
            'nip' => ['required', 'numeric', 'digits_between:1,20', Rule::unique('pegawai','nip')->ignore($pegawai->id)],
            'nama' => 'required|max:50|regex:/^[\pL\s]+$/u',
            'foto_profil' => 'nullable|image|max:2048',
            'status' => 'required|in:aktif,pensiun,berhenti',
            'kelompok' => 'required|in:pegawai,kendaraan,admin,supir',
            'username' => ['required', 'max:15', 'alpha_dash:ascii', 'lowercase', Rule::unique('pegawai','username')->ignore($pegawai->id)],
            'password' => ['required', 'min:8', 'max:50', 'regex:/^.*(?!.*\s)(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\!\@\#\$\%\^\&\*\(\)\-\=\¡\£\_\+\`\~\.\,\<\>\/\?\;\:\'\"\\\|\[\]\{\}]).*$/',],
        ],$messages)->validate();

        $data = [
            'nip' => $request->input('nip'),
            'nama' => $request->input('nama'),
            'status' => $request->input('status'),
            'kelompok' => $request->input('kelompok'),
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
        ];

        if ($request->hasFile('foto_profil')) {
            if (File::exists($pegawai->foto_profil)) {
                File::delete($pegawai->foto_profil);
            }

            $newImage = $request->file('foto_profil');
            $imageName = time().'.'.$newImage->extension();
            $newImage->move(public_path('images'), $imageName);

            $pegawai->foto_profil = 'images/' . $imageName;
        }

        $pegawai->update($data);
        $pegawai->save();

        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->success('<b>Berhasil!</b><br>Data pegawai sudah diperbarui.');
        
        return redirect(route('admin.data.pegawai'));
    }

    function deletepegawai(pegawai $pegawai)
    {
        pegawai::destroy($pegawai->id);

        if (File::exists(public_path($pegawai->foto_profil))) {
            File::delete(public_path($pegawai->foto_profil));
        }

        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->success('<b>Berhasil!</b><br>Data pegawai sudah dihapus.');

        return redirect(route('admin.data.pegawai'));
    }
}
