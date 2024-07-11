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
            'pengajuan_jumlah.min' => 'Jumlah kendaraan tidak dapat nol',
            'pengajuan_jumlah.max' => "Jumlah kendaraan maksimal adalah $jumlah_kendaraan",
            'pengajuan_tanggal_awal.after_or_equal' => 'Tanggal tidak valid',
            'pengajuan_tanggal_akhir.after_or_equal' => 'Tanggal tidak valid',            
        ];

        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->error('Pengajuan gagal.');

        $request->validate([
            'pengajuan_jumlah' => "required|numeric|min:1|max:$jumlah_kendaraan",
            'pengajuan_tanggal_awal' => 'required|date|after_or_equal:today',
            'pengajuan_tanggal_akhir' => "required|date|after_or_equal:$request->pengajuan_tanggal_awal",
            'pengajuan_supir' => 'nullable',
        ],$messages);

        $data = [
            'nip_peminjam' => Auth::user()->nip,
            'jumlah' => $request->input('pengajuan_jumlah'),
            'tanggal_awal' => $request->input('pengajuan_tanggal_awal'),
            'tanggal_akhir' => $request->input('pengajuan_tanggal_akhir'),
            'supir' => $request->input('pengajuan_supir'),
        ];

        peminjaman::create($data);

        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->success('Pengajuan berhasil.');

        return redirect('/homepage_pegawai');
    }

    function editpeminjaman(Request $request, string $id)
    {
        $jumlah_kendaraan = kendaraan::where('status','tersedia')->where('kondisi','baik')->count();

        $messages = [
            'required' => 'Kolom :attribute belum terisi.',
            'numeric' => 'Kolom :attribute hanya boleh berisi angka',
            'date' => 'Kolom :attribute harus berupa tanggal',
            'ubah_jumlah.min' => 'Jumlah kendaraan tidak dapat nol',
            'ubah_jumlah.max' => "Jumlah kendaraan maksimal adalah $jumlah_kendaraan",
            'ubah_tanggal_awal.after_or_equal' => 'Tanggal tidak valid',
            'ubah_tanggal_akhir.after_or_equal' => 'Tanggal tidak valid',            
        ];

        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->error('Peminjaman gagal diubah.');

        $request->validate([
            'ubah_jumlah' => "required|numeric|min:1|max:$jumlah_kendaraan",
            'ubah_tanggal_awal' => 'required|date|after_or_equal:today',
            'ubah_tanggal_akhir' => "required|date|after_or_equal:$request->ubah_tanggal_awal",
            'ubah_supir' => 'nullable',
        ],$messages);

        // $validator = Validator::make($request->all(), [
        //     'ubah_jumlah' => "required|numeric|min:1|max:$jumlah_kendaraan",
        //     'ubah_tanggal_awal' => 'required|date|after_or_equal:today',
        //     'ubah_tanggal_akhir' => "required|date|after_or_equal:$request->ubah_tanggal_awal",
        //     'ubah_supir' => 'nullable',
        // ],$messages)->validateWithBag($id);
 
        // return redirect('/homepage_pegawai')->withErrors($validator, $id);

        peminjaman::where('id',$id)->update([
            'jumlah' => $request->input('ubah_jumlah'),
            'tanggal_awal' => $request->input('ubah_tanggal_awal'),
            'tanggal_akhir' => $request->input('ubah_tanggal_akhir'),
            'supir' => $request->input('ubah_supir'),
        ]);

        flash()
        ->killer(true)
        ->layout('bottomRight')
        ->timeout(3000)
        ->success('Peminjaman berhasil diubah.');

        return redirect('/homepage_pegawai');
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
        ->error('Proses verifikasi gagal.');

       

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
        ->success('Proses verifikasi berhasil.');
        
        return redirect('/data_peminjaman');
    }

    function selesaipeminjaman(peminjaman $peminjaman)
    {
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

        return redirect('/data_peminjaman');
    }
}