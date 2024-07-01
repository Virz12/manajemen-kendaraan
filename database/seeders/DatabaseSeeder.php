<?php

namespace Database\Seeders;

use App\Models\kendaraan;
use App\Models\pegawai;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $data_pegawai = [
            [
                'nip' => '1000000000',
                'nama' => 'Admin',
                'status' => 'aktif',
                'kelompok' => 'admin',
                'username' => 'masadmin',
                'password' => bcrypt('masadmin')
            ],[
                'nip' => '1000000001',
                'nama' => 'Pegawai',
                'status' => 'aktif',
                'kelompok' => 'pegawai',
                'username' => 'maspegawai',
                'password' => bcrypt('maspegawai')
            ],[
                'nip' => '1000000002',
                'nama' => 'Kendaraan',
                'status' => 'aktif',
                'kelompok' => 'kendaraan',
                'username' => 'maskendaraan',
                'password' => bcrypt('maskendaraan')
            ],[
                'nip' => '1000000003',
                'nama' => 'Supir Satu',
                'status' => 'aktif',
                'kelompok' => 'supir',
                'username' => 'supir1',
                'password' => bcrypt('supir')
            ],[
                'nip' => '1000000004',
                'nama' => 'Supir Dua',
                'status' => 'aktif',
                'kelompok' => 'supir',
                'username' => 'supir2',
                'password' => bcrypt('supir')
            ],[
                'nip' => '1000000005',
                'nama' => 'Supir Tiga',
                'status' => 'aktif',
                'kelompok' => 'supir',
                'username' => 'supir3',
                'password' => bcrypt('supir')
            ],[
                'nip' => '1000000006',
                'nama' => 'Supir Empat',
                'status' => 'aktif',
                'kelompok' => 'supir',
                'username' => 'supir4',
                'password' => bcrypt('supir')
            ],[
                'nip' => '1000000007',
                'nama' => 'Supir Lima',
                'status' => 'aktif',
                'kelompok' => 'supir',
                'username' => 'supir5',
                'password' => bcrypt('supir')
            ],[
                'nip' => '1000000008',
                'nama' => 'Supir Enam',
                'status' => 'aktif',
                'kelompok' => 'supir',
                'username' => 'supir6',
                'password' => bcrypt('supir')
            ],[
                'nip' => '1000000009',
                'nama' => 'Supir Tujuh',
                'status' => 'aktif',
                'kelompok' => 'supir',
                'username' => 'supir7',
                'password' => bcrypt('supir')
            ],[
                'nip' => '1000000010',
                'nama' => 'Supir Delapan',
                'status' => 'aktif',
                'kelompok' => 'supir',
                'username' => 'supir8',
                'password' => bcrypt('supir')
            ],[
                'nip' => '1000000011',
                'nama' => 'Supir Sembilan',
                'status' => 'aktif',
                'kelompok' => 'supir',
                'username' => 'supir9',
                'password' => bcrypt('supir')
            ],[
                'nip' => '1000000012',
                'nama' => 'Supir Sepuluh',
                'status' => 'aktif',
                'kelompok' => 'supir',
                'username' => 'supir10',
                'password' => bcrypt('supir')
            ],[
                'nip' => '1000000013',
                'nama' => 'Supir Sebelas',
                'status' => 'aktif',
                'kelompok' => 'supir',
                'username' => 'supir11',
                'password' => bcrypt('supir')
            ],[
                'nip' => '1000000014',
                'nama' => 'Supir DuaBelas',
                'status' => 'aktif',
                'kelompok' => 'supir',
                'username' => 'supir12',
                'password' => bcrypt('supir')
            ],[
                'nip' => '1000000015',
                'nama' => 'Supir TigaBelas',
                'status' => 'aktif',
                'kelompok' => 'supir',
                'username' => 'supir13',
                'password' => bcrypt('supir')
            ],[
                'nip' => '1000000016',
                'nama' => 'Supir EmpatBelas',
                'status' => 'aktif',
                'kelompok' => 'supir',
                'username' => 'supir14',
                'password' => bcrypt('supir')
            ],[
                'nip' => '1000000017',
                'nama' => 'Supir LimaBelas',
                'status' => 'aktif',
                'kelompok' => 'supir',
                'username' => 'supir15',
                'password' => bcrypt('supir')
            ]
        ];

        $data_kendaraan = [
            [
                'jenis_kendaraan' => 'Toyota Avanza',
                'tahun' => '2014',
                'nopol' => 'DK1234RFS',
                'warna' => 'Hitam',
                'foto_kendaraan' => 'images/1.jpg',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Toyota Ventuner',
                'tahun' => '2020',
                'nopol' => 'KT1234RFS',
                'warna' => 'Putih',
                'foto_kendaraan' => 'images/2.jpg',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Toyota Rush',
                'tahun' => '2018',
                'nopol' => 'AA1234RFS',
                'warna' => 'Silver',
                'foto_kendaraan' => 'images/3.jpg',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Toyota Veloz',
                'tahun' => '2000',
                'nopol' => 'Z1234RFS',
                'warna' => 'Biru',
                'foto_kendaraan' => 'images/4.jpg',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Toyota Kijang',
                'tahun' => '2000',
                'nopol' => 'F1234RFS',
                'warna' => 'Hitam',
                'foto_kendaraan' => 'images/5.jpg',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Ford Ranger',
                'tahun' => '2010',
                'nopol' => 'A1234RFS',
                'warna' => 'Abu',
                'foto_kendaraan' => 'images/6.jpg',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Nissan GTR',
                'tahun' => '2017',
                'nopol' => 'AB1234RFS',
                'warna' => 'Kuning',
                'foto_kendaraan' => 'images/7.jpg',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Suzuki Carry Futura',
                'tahun' => '2019',
                'nopol' => 'DK1234ACR',
                'warna' => 'Putih',
                'foto_kendaraan' => 'images/8.jpg',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'MercedezBenz Arocs 2842',
                'tahun' => '2018',
                'nopol' => 'KT1234AFR',
                'warna' => 'Putih',
                'foto_kendaraan' => 'images/9.jpg',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Volvo FH16 750',
                'tahun' => '2022',
                'nopol' => 'BK2542SXE',
                'warna' => 'Biru',
                'foto_kendaraan' => 'images/10.jpg',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Mitshubishi Canter',
                'tahun' => '2017',
                'nopol' => 'B2542SXE',
                'warna' => 'Kuning',
                'foto_kendaraan' => 'images/11.jpg',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Hino Dutro',
                'tahun' => '2016',
                'nopol' => 'E2542BGZ',
                'warna' => 'Hijau',
                'foto_kendaraan' => 'images/12.jpg',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Isuzu ELF NMR81',
                'tahun' => '2019',
                'nopol' => 'A2542SXE',
                'warna' => 'Putih',
                'foto_kendaraan' => 'images/13.jpg',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Toyota Dyna',
                'tahun' => '1997',
                'nopol' => 'A2725SL',
                'warna' => 'Hijau',
                'foto_kendaraan' => 'images/14.jpg',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Mitshubishi Xpander',
                'tahun' => '2022',
                'nopol' => 'B1234ZXE',
                'warna' => 'Hitam',
                'foto_kendaraan' => 'images/15.jpg',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ]
        ];

        foreach($data_pegawai as $data) {
            pegawai::create($data);
        }

        foreach($data_kendaraan as $data) {
            kendaraan::create($data);
        }
    }
}
