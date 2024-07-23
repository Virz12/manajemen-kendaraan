<?php

namespace App\Http\Controllers;

use App\Models\detail_peminjaman;
use App\Models\kendaraan;
use App\Models\notification;
use App\Models\pegawai;
use App\Models\peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PeminjamanController extends Controller
{
    function storepeminjaman(Request $request)
    {
        $jumlah_kendaraan = kendaraan::where('status','tersedia')->where('kondisi','baik')->count();

        $messages = [
            'required' => 'Kolom :attribute belum terisi.',
            'numeric' => 'Kolom :attribute hanya boleh berisi angka',
            'date' => 'Kolom :attribute harus berupa tanggal',
            'jumlah.min' => 'Jumlah kendaraan tidak dapat nol',
            'jumlah.max' => "Jumlah kendaraan maksimal adalah $jumlah_kendaraan",
            'tanggal_awal.after_or_equal' => 'Tanggal tidak valid',
            'tanggal_akhir.after_or_equal' => 'Tanggal tidak valid',            
        ];

        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->error('<b>Error!</b><br>Peminjaman gagal diajukan.');

        Validator::make($request->all(), [
            'jumlah' => "required|numeric|min:1|max:$jumlah_kendaraan",
            'tanggal_awal' => 'required|date|after_or_equal:today',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'supir' => 'nullable|numeric',
        ],$messages)->validateWithBag('pengajuan');

        $data = [
            'nip_peminjam' => Auth::user()->nip,
            'jumlah' => $request->input('jumlah'),
            'tanggal_awal' => $request->input('tanggal_awal'),
            'tanggal_akhir' => $request->input('tanggal_akhir'),
            'supir' => $request->input('supir'),
        ];

        peminjaman::create($data);

        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->success('<b>Berhasil!</b><br>Peminjaman sudah diajukan.');

        return redirect(route('pegawai.homepage'));
    }

    function editpeminjaman(peminjaman $peminjaman, Request $request)
    {
        $jumlah_kendaraan = kendaraan::where('status','tersedia')->where('kondisi','baik')->count();

        $messages = [
            'required' => 'Kolom :attribute belum terisi.',
            'numeric' => 'Kolom :attribute hanya boleh berisi angka',
            'date' => 'Kolom :attribute harus berupa tanggal',
            'jumlah.min' => 'Jumlah kendaraan tidak dapat nol',
            'jumlah.max' => "Jumlah kendaraan maksimal adalah $jumlah_kendaraan",
            'tanggal_awal.after_or_equal' => 'Tanggal tidak valid',
            'tanggal_akhir.after_or_equal' => 'Tanggal tidak valid',
        ];

        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->error('<b>Error!</b><br>Data peminjaman gagal diperbarui.');

        Validator::make($request->all(), [
            'jumlah' => "required|numeric|min:1|max:$jumlah_kendaraan",
            'tanggal_awal' => 'required|date|after_or_equal:today',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'supir' => 'nullable|numeric',
        ],$messages)->validateWithBag($peminjaman->id);

        $peminjaman->update([
            'jumlah' => $request->input('jumlah'),
            'tanggal_awal' => $request->input('tanggal_awal'),
            'tanggal_akhir' => $request->input('tanggal_akhir'),
            'supir' => $request->input('supir'),
        ]);

        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->success('<b>Berhasil!</b><br>Data peminjaman sudah diperbarui.');

        return redirect(route('pegawai.homepage'));
    }

    function pageverifikasipeminjaman(peminjaman $peminjaman)
    {   
        $data_kendaraan = kendaraan::where('status','tersedia')->where('kondisi','baik')->get();
        $data_supir = pegawai::where('kelompok','supir')->where('status','aktif')->get();

        return view('kendaraan.verifikasi_peminjaman')
                ->with('data_kendaraan',$data_kendaraan)
                ->with('data_supir',$data_supir)
                ->with('peminjaman',$peminjaman);
    }

    function verifikasipeminjaman(peminjaman $peminjaman, Request $request)
    {
        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->error('<b>Error!</b><br>Data peminjaman gagal diverifikasi.');

        foreach($request->input('kendaraan') as $data)
        {
            $item = new detail_peminjaman();
            $item->nopol = kendaraan::find($data)->nopol;
            if ($request->collect('supir')->has($data)) {
                $item->id_supir = kendaraan::find($data)->supir->id;
            }else{
                $item->id_supir = $peminjaman->pegawai->id;
            }
            $item->id_peminjaman = $peminjaman->id;
            $item->id_pegawai = Auth::id();
            $item->save();
        }

        $data_kendaraan = $request->input('kendaraan');
        foreach($data_kendaraan as $kendaraan)
        {
            kendaraan::where('id',$kendaraan)->update([
                'status' => 'digunakan',
            ]);
        }
        $peminjaman->update([
            'status' => 'diterima',
        ]);
        $notification = [
            'id_pegawai' => $peminjaman->pegawai->id,
            'id_peminjaman' => $peminjaman->id,
            'notification' => 'Peminjaman Anda telah diterima!'
        ];
        notification::create($notification);

        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->success('<b>Berhasil!</b><br>Data peminjaman sudah diverifikasi.');
        
        return redirect(route('kendaraan.data.peminjaman'));
    }

    function selesaipeminjaman(peminjaman $peminjaman)
    {
        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->error('<b>Error!</b><br>Peminjaman gagal diselesaikan.');

        $detail_peminjaman = detail_peminjaman::where('id_peminjaman',$peminjaman->id)->get();

        foreach($detail_peminjaman as $detailpeminjaman)
        {
            $kendaraan[] = $detailpeminjaman->nopol;
        }

        foreach($kendaraan as $nopol)
        {
            kendaraan::where('nopol',$nopol)->update([
                'status' => 'tersedia',
            ]);
        }

        $peminjaman->update([
            'status' => 'selesai',
        ]);

        notification::where('id_peminjaman',$peminjaman->id)->delete();

        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->success('<b>Berhasil!</b><br>Peminjaman sudah diselesaikan.');

        return redirect(route('kendaraan.data.peminjaman'));
    }
}
