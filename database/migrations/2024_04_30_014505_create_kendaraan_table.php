<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('jenis_kendaraan',50);
            $table->foreignUuid('id_supir')->nullable()->references('id')->on('pegawai')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('tahun',4);
            $table->string('nopol',15)->unique();
            $table->string('warna',15);
            $table->string('foto_kendaraan');
            $table->enum('kondisi',['baik','rusak','perbaikan'])->default('baik');
            $table->enum('status',['tersedia','digunakan'])->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};
