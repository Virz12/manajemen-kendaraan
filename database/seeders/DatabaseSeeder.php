<?php

namespace Database\Seeders;

use App\Models\detail_peminjaman;
use App\Models\kendaraan;
use App\Models\pegawai;
use App\Models\peminjaman;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $data_pegawai = [
            [
                'id' => '1',
                'nip' => '123456789',
                'nama' => 'Admin',
                'status' => 'aktif',
                'kelompok' => 'admin',
                'username' => 'MasAdmin',
                'password' => bcrypt('MasAdmin')
            ],[
                'id' => '2',
                'nip' => '987654321',
                'nama' => 'Pegawai',
                'status' => 'aktif',
                'kelompok' => 'pegawai',
                'username' => 'MasPegawai',
                'password' => bcrypt('MasPegawai')
            ],[
                'id' => '3',
                'nip' => '1234567890',
                'nama' => 'Kendaraan',
                'status' => 'aktif',
                'kelompok' => 'kendaraan',
                'username' => 'MasKendaraan',
                'password' => bcrypt('MasKendaraan')
            ],[
                'id' => '4',
                'nip' => '0987654321',
                'nama' => 'Supir',
                'status' => 'aktif',
                'kelompok' => 'supir',
                'username' => 'MasSupir',
                'password' => bcrypt('MasSupir')
            ]
        ];

        $data_kendaraan = [
            [
                'jenis_kendaraan' => 'Pickup',
                'tahun' => '2000',
                'nopol' => 'DK1234RFS',
                'warna' => 'Hitam',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ]
        ];

        $data_peminjaman = [
            [
                'nip_peminjam' => '123456789',
                'jumlah' => '1',
                'tanggal_awal' => '2001-01-01',
                'tanggal_akhir' => '2002-01-01',
                'status' => 'pengajuan'
            ]
        ];

        $data_detail_peminjaman = [
            [
                'nopol' => 'DK1234RFS',
                'id_peminjaman' => '1',
                'id_pegawai' => '3',
                'id_supir' => '4'
            ]
        ];

        foreach($data_pegawai as $data) {
            pegawai::create($data);
        }

        foreach($data_kendaraan as $data) {
            kendaraan::create($data);
        }

        foreach($data_peminjaman as $data) {
            peminjaman::create($data);
        }

        foreach($data_detail_peminjaman as $data) {
            detail_peminjaman::create($data);
        }
    }
}
