<?php

namespace App\Http\Controllers;

use App\Models\detail_peminjaman;
use App\Models\kendaraan;
use App\Models\pegawai;
use App\Models\peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    function pegawai()
    {
        $datapegawai = pegawai::orderBy('nip','DESC')->paginate(6);

        return view('admin.pegawai')
                ->with('datapegawai',$datapegawai);
    }

    function kendaraan()
    {
        $datakendaraan = kendaraan::orderBy('status','DESC')->paginate(6);

        return view('admin.kendaraan')
                ->with('datakendaraan',$datakendaraan);
    }

    function peminjaman()
    {
        $datapeminjaman = peminjaman::orderBy('status','DESC')->paginate(6);
        $datadetail_peminjaman = detail_peminjaman::orderBy('id_pegawai','DESC')->paginate(6);

        return view('admin.peminjaman')
                ->with('datapeminjaman',$datapeminjaman)
                ->with('datadetail_peminjaman',$datadetail_peminjaman) ;
    }
    
    function createpegawai()
    {
        return view('admin.tambah_pegawai');
    }

    function storepegawai(Request $request)
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
            'nip' => 'required|numeric|digits_between:1,20|unique:pegawai,nip',
            'nama' => 'required|regex:/^[\pL\s]+$/u',
            'foto_profil' => 'nullable|image|max:2048',
            'kelompok' => 'required|alpha',
            'username' => 'required|max:15|alpha_dash|unique:pegawai,username',
            'password' => 'required|alpha_num',
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
            'nip' => 'required|numeric|digits_between:1,20',
            'nama' => 'required|regex:/^[\pL\s]+$/u',
            'foto_profil' => 'nullable|image|max:2048',
            'kelompok' => 'required|alpha',
            'username' => 'required|max:15|alpha_dash',
            'password' => 'required|alpha_num',
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
