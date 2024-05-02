<?php

namespace App\Http\Controllers;

use App\Models\pegawai;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    function tambahpegawai()
    {
        return view('admin.tambah_pegawai');
    }

    function simpanpegawai(Request $request)
    {
        $messages = [
            'required' => 'Kolom :attribute wajib diisi.',
            'alpha' => 'Kolom :attribute hanya boleh berisi huruf.',
            'alpha_dash' => 'Kolom :attribute hanya boleh berisi huruf.',
            'size' => 'Kolom :attribute tidak boleh lebih dari 20 karakter',
            'numeric' => 'Kolom :attribute hanya boleh berisi angka',
            'unique' => ':attribute sudah dipakai',
            'regex:/^[\pL\s]+$/u' => 'Kolom :attribute hanya boleh berisi huruf.',
            'image' => 'File Harus Berupa Gambar.',
            'max:15' => 'Maksimal 15 Karakter.',
            'max:2048' => 'Ukuran file Maksimal 2MB.',
            'digits_between:1,20' => 'Maksimal angka 20 Digit.',
        ];

        $request->validate([
            'nip' => 'required|numeric|digits_between:1,20|unique:pegawai,nip',
            'nama' => 'required|regex:/^[\pL\s]+$/u',
            'foto_profil' => 'nullable|image|max:2048',
            'kelompok' => 'required|alpha',
            'username' => 'required|max:15|alpha_dash|unique:pegawai,username',
            'password' => 'required',
        ],$messages);
 
        $data = [
            'nip' => $request->nip,
            'nama' => $request->nama,
            'kelompok' => $request->kelompok,
            'username' => $request->username,
            'password' => $request->password,
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

        return redirect('/dashboard_admin');
    }
}
