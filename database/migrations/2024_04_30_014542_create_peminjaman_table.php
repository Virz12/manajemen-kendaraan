<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nip_peminjam')->references('nip')->on('pegawai')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('jumlah');
            $table->date('tanggal_awal');
            $table->date('tanggal_akhir');
            $table->integer('supir');
            $table->enum('status',['pengajuan','diterima','selesai'])->default('pengajuan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
