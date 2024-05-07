<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    function login()
    {
        return view('login.login');
    }

    function storelogin(Request $request)
    {
        $messages = [
            'required' => 'Kolom :attribute wajib diisi.',
            'alpha' => 'Kolom :attribute hanya boleh berisi huruf.',
            'size' => 'Kolom :attribute tidak boleh lebih dari 10 karakter',
            'numeric' => 'Kolom :attribute hanya boleh berisi angka',
            'unique' => ':attribute sudah dipakai',
            'regex:/^[\pL\s]+$/u' => 'Kolom :attribute hanya boleh berisi huruf.'
        ];

        $request->validateWithBag('errors',[
            'username' => 'required|regex:/^[\pL\s]+$/u',
            'password' => 'required' 
        ],$messages);

        $inputeddata = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if(Auth::attempt($inputeddata)) {
            if (Auth::user()->kelompok == 'admin') {
                return redirect('/dashboard_admin');
            }elseif (Auth::user()->kelompok == 'pegawai') {
                return redirect('/homepage_pegawai');
            }elseif (Auth::user()->kelompok == 'kendaraan') {
                return redirect('/dashboard_kendaraan');
            }
        }else {
            return redirect('/login')->withErrors(['username' => 'Nama Pengguna dan Sandi Tidak Sesuai'])->withInput();
        }
    }

    function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
