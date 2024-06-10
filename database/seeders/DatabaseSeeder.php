<?php

namespace Database\Seeders;

use App\Models\kendaraan;
use App\Models\pegawai;
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
                'nip' => '1122334455',
                'nama' => 'Admin',
                'status' => 'aktif',
                'kelompok' => 'admin',
                'username' => 'masadmin',
                'password' => bcrypt('masadmin')
            ],[
                'id' => '2',
                'nip' => '2233445566',
                'nama' => 'Pegawai',
                'status' => 'aktif',
                'kelompok' => 'pegawai',
                'username' => 'maspegawai',
                'password' => bcrypt('maspegawai')
            ],[
                'id' => '3',
                'nip' => '3344556677',
                'nama' => 'Kendaraan',
                'status' => 'aktif',
                'kelompok' => 'kendaraan',
                'username' => 'maskendaraan',
                'password' => bcrypt('maskendaraan')
            ],[
                'id' => '4',
                'nip' => '4455667788',
                'nama' => 'Supir',
                'status' => 'aktif',
                'kelompok' => 'supir',
                'username' => 'massupir',
                'password' => bcrypt('maskendaraan')
            ],[
                'id' => '5',
                'nip' => '5566778899',
                'nama' => 'Arie',
                'status' => 'aktif',
                'kelompok' => 'supir',
                'username' => 'masarie',
                'password' => bcrypt('masarie')
            ],[
                'id' => '6',
                'nip' => '6677889900',
                'nama' => 'Arif',
                'status' => 'aktif',
                'kelompok' => 'supir',
                'username' => 'masarif',
                'password' => bcrypt('masarif')
            ],[
                'id' => '7',
                'nip' => '7788990011',
                'nama' => 'Andi',
                'status' => 'aktif',
                'kelompok' => 'supir',
                'username' => 'masandi',
                'password' => bcrypt('masandi')
            ],[
                'id' => '8',
                'nip' => '8899001122',
                'nama' => 'Budi',
                'status' => 'aktif',
                'kelompok' => 'supir',
                'username' => 'masbudi',
                'password' => bcrypt('masbudi')
            ]
        ];

        $data_kendaraan = [
            [
                'jenis_kendaraan' => 'Toyota Avanza',
                'tahun' => '2014',
                'nopol' => 'DK1234RFS',
                'warna' => 'Hitam',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Toyota Ventuner',
                'tahun' => '2020',
                'nopol' => 'KT1234RFS',
                'warna' => 'Putih',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Toyota Rush',
                'tahun' => '2018',
                'nopol' => 'AA1234RFS',
                'warna' => 'SIlver',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Toyota Veloz',
                'tahun' => '2000',
                'nopol' => 'Z1234RFS',
                'warna' => 'Biru',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Toyota Kijang',
                'tahun' => '2000',
                'nopol' => 'F1234RFS',
                'warna' => 'Hitam',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Ford Ranger',
                'tahun' => '2010',
                'nopol' => 'A1234RFS',
                'warna' => 'Abu',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Nissan GTR',
                'tahun' => '2017',
                'nopol' => 'AB1234RFS',
                'warna' => 'Kuning',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Suzuki Carry Futura',
                'tahun' => '2019',
                'nopol' => 'DK1234ACR',
                'warna' => 'Putih',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'MercedezBenz Arocs 2842',
                'tahun' => '2018',
                'nopol' => 'KT1234AFR',
                'warna' => 'Putih',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Volvo FH16 750',
                'tahun' => '2022',
                'nopol' => 'BK2542SXE',
                'warna' => 'Biru',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Mitshubishi Canter',
                'tahun' => '2017',
                'nopol' => 'B2542SXE',
                'warna' => 'Kuning',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Hino Dutro',
                'tahun' => '2016',
                'nopol' => 'E2542BGZ',
                'warna' => 'Hijau',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Isuzu ELF NMR81',
                'tahun' => '2019',
                'nopol' => 'A2542SXE',
                'warna' => 'Putih',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Toyota Dyna',
                'tahun' => '1997',
                'nopol' => 'A2725SL',
                'warna' => 'Hijau',
                'kondisi' => 'baik',
                'status' => 'tersedia'
            ],[
                'jenis_kendaraan' => 'Mitshubishi Xpander',
                'tahun' => '2022',
                'nopol' => 'B1234ZXE',
                'warna' => 'Hitam',
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
