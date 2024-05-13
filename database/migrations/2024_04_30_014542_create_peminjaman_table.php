<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('nip_peminjam')->references('nip')->on('pegawai');
            $table->integer('jumlah');
            $table->date('tanggal_awal');
            $table->date('tanggal_akhir');
            $table->boolean('supir')->default(false)->nullable();
            $table->enum('status',['pengajuan','diterima','selesai'])->default('pengajuan');
            $table->timestamps();
        });

        Schema::create('detail_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('nopol')->references('nopol')->on('kendaraan');
            $table->integer('id_peminjaman')->references('id')->on('peminjaman');
            $table->integer('id_pegawai')->references('id')->on('pegawai');
            $table->integer('id_supir')->references('id')->on('pegawai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
        Schema::dropIfExists('detail_peminjaman');
    }
};
