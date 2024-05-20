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
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_kendaraan',50);
            $table->string('tahun',4);
            $table->string('nopol',15)->unique();
            $table->string('warna',15);
            $table->enum('kondisi',['baik','rusak','perbaikan'])->default('baik');
            $table->enum('status',['tersedia','digunakan'])->default('tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};
