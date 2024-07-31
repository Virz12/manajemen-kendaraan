# Sistem Informasi Kendaraan

Sistem informasi kendaraan memungkinkan pengguna untuk melakukan serangkaian operasi, seperti; 

Manajemen Kendaraan
- Menambah data kendaraan.
- Merubah data kendaraan.
- Menghapus kendaraan.
- Melihat jumlah kendaraan, status serta kondisi kendaraan.
- Melihat jumlah pengajuan peminjaman kendaraan.
- Melihat arsip data peminjaman kendaraan.
- Memverifkasi pengajuan peminjaman kendaraan.

Admin
- Menambah data pegawai.
- Merubah data pegawai.
- Mengahapus data pegawai
- Melihat jumlah kendaraan, status, serta kondisi kendaraan.
- Melihat jumlah pengajuan peminjaman kendaraan.
- Melihat arsip data peminjaman kendaraan.

Pegawai
- Melakukan pengajuan peminjaman kendaraan.
- Merubah data peminjaman yang diajukan.
- Melihat riwayat data peminjaman yang diajukan sebelumnya.

## Persyaratan Sistem

- [Laravel](https://laravel.com).
- [Composer](https://getcomposer.org).
- [NPM](https://nodejs.org/en/download/package-manager).
- [PHP](https://www.php.net/downloads.php).

Generate key aplikasi
```bash
php artisan key:generate  
```
Install package Composer
```bash
composer install
```
Install package NPM
```bash
npm install  
```
Perbarui package Composer
```bash
composer update  
```
Perbarui package NPM
```bash
npm update  
```
Jalankan Laravel migration
```bash
php artisan migrate
```
Jalankan Laravel database seeder
```bash
php artisan db:seed
```
