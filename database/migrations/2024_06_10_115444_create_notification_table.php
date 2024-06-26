<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_pegawai')->references('id')->on('pegawai')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('id_peminjaman')->references('id')->on('peminjaman')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('notification',255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification');
    }
};
