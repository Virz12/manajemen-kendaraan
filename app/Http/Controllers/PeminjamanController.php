<?php

namespace App\Http\Controllers;

use App\Models\detail_peminjaman;
use App\Models\kendaraan;
use App\Models\notification;
use App\Models\pegawai;
use App\Models\peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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

        $request->validate([
            'pengajuan_jumlah' => "required|numeric|min:1|max:$jumlah_kendaraan",
            'pengajuan_tanggal_awal' => 'required|date|after_or_equal:today',
            'pengajuan_tanggal_akhir' => "required|date|after_or_equal:$request->tanggal_awal",
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

        return redirect('/homepage_pegawai')
                ->with('notification', 'Pengajuan Berhasil');
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

        $request->validate([
            'ubah_jumlah' => "required|numeric|min:1|max:$jumlah_kendaraan",
            'ubah_tanggal_awal' => 'required|date|after_or_equal:today',
            'ubah_tanggal_akhir' => "required|date|after_or_equal:$request->tanggal_awal",
            'ubah_supir' => 'nullable',
        ],$messages);

        peminjaman::where('id',$id)->update([
            'jumlah' => $request->input('ubah_jumlah'),
            'tanggal_awal' => $request->input('ubah_tanggal_awal'),
            'tanggal_akhir' => $request->input('ubah_tanggal_akhir'),
            'supir' => $request->input('ubah_supir'),
        ]);

        return redirect('/homepage_pegawai')
                ->with('notification', 'Peminjaman Berhasil Diubah!');
    }

    function pageverifikasipeminjaman(string $id)
    {   
        $datapeminjam = peminjaman::findOrFail($id);
        $datakendaraan = kendaraan::where('status','tersedia')->where('kondisi','baik')->get();
        $datasupir = pegawai::where('kelompok','supir')->where('status','aktif')->get();

        return view('kendaraan.verifikasi_peminjaman')
                ->with('datakendaraan',$datakendaraan)
                ->with('datasupir',$datasupir)
                ->with('datapeminjam',$datapeminjam);
    }

    function verifikasipeminjaman(Request $request, string $id)
    {
        $peminjaman = peminjaman::findOrFail($id);

        $messages = [
            'nopol.required' => 'Data kendaraan belum terisi',
            'nopol.size' => 'Jumlah kendaraan tidak sesuai permintaan',
            'id_supir.size' => 'Jumlah supir tidak sesuai',
            'id_supir.required' => 'Data supir belum terisi',
        ];

        if($peminjaman->supir == 1)
        {
            $request->validate([
                'nopol' => "required|size:$peminjaman->jumlah",
                'id_supir' => "required|size:$peminjaman->jumlah",
            ], $messages);
        }else{
            $request->validate([
                'nopol' => "required|size:$peminjaman->jumlah",
                'id_supir' => "nullable|size:$peminjaman->jumlah",
            ], $messages);    
        }
        
        $kendaraan = $request->input('nopol');
        $supir = $request->input('id_supir');
        $count = count($kendaraan);

        if($supir == null)
        {
            foreach($kendaraan as $data)
            {
                $item = new detail_peminjaman();
                $item->nopol = $data;
                $item->id_peminjaman = $id;
                $item->id_pegawai = Auth::id();
                $item->save();
            }
        }else {
            for ($i = 0; $i < $count; $i++) {
                $item = new detail_peminjaman();
                $item->nopol = $kendaraan[$i];
                $item->id_supir = $supir[$i];
                $item->id_peminjaman = $id;
                $item->id_pegawai = Auth::id();
                $item->save();
            }
        }
        
        foreach($kendaraan as $data)
        {
            kendaraan::where('nopol',$data)->update([
                'status' => 'digunakan',
            ]);
        }

        peminjaman::where('id',$id)->update([
            'status' => 'diterima',
        ]);

        $notifikasi = [
            'id_pegawai' => $peminjaman->pegawai->id,
            'notification' => 'Peminjaman Anda telah diterima!'
        ];

        notification::create($notifikasi);
        
        return redirect('/data_peminjaman')
                ->with('notification', 'Berhasil Diverifikasi.');
    }

    function selesaipeminjaman(string $id)
    {
        $detail_peminjaman = detail_peminjaman::where('id_peminjaman',$id)->get();

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

        peminjaman::where('id',$id)->update([
            'status' => 'selesai',
        ]);

        return redirect('/data_peminjaman');
    }
}
