<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_peminjaman', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nopol')->references('nopol')->on('kendaraan')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('id_peminjaman')->references('id')->on('peminjaman')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('id_pegawai')->references('id')->on('pegawai')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('id_supir')->references('id')->on('pegawai')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
};
