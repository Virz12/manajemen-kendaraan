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
            'required' => 'Kolom :attribute belum terisi.',
            'max' => 'Kolom :attribute maksimal berisi 15 karakter.',
            'alpha_dash' => 'Kolom :attribute hanya boleh berisi huruf, angka, (-), (_).',
            'alpha_num' => 'Kolom :attribute hanya boleh berisi huruf dan angka',
            'lowercase' => 'Kolom :attribute hanya dapat diisi huruf kecil',
        ];

        $request->validate([
            'username' => 'required|max:15|alpha_dash:ascii|lowercase',
            'password' => 'required|max:15|alpha_num:ascii', 
        ],$messages);

        $inputeddata = [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ];

        if(Auth::attempt($inputeddata)) {
            if (Auth::user()->kelompok == 'admin') {
                return redirect(route('admin.dashboard'));
            }elseif (Auth::user()->kelompok == 'pegawai') {
                return redirect(route('pegawai.homepage'));
            }elseif (Auth::user()->kelompok == 'kendaraan') {
                return redirect(route('kendaraan.dashboard'));
            }
        }else {
            return redirect(route('login'))
                ->withErrors([
                    'username' => 'Nama Pengguna atau Sandi Tidak Sesuai',
                    'password' => 'Nama Pengguna atau Sandi Tidak Sesuai'
                ])->withInput();
        }
    }

    function logout()
    {
        Auth::logout();
        return redirect(route('login'));
    }
}
