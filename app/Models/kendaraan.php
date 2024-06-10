<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class kendaraan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'jenis_kendaraan',
        'tahun',
        'nopol',
        'warna',
        'foto_kendaraan',
        'kondisi',
        'status',
    ];
    protected $table = 'kendaraan';

    public function detail_peminjaman(): BelongsTo
    {
        return $this->belongsTo(detail_peminjaman::class, 'nopol', 'nopol');
    }
}
