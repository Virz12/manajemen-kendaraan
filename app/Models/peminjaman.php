<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class peminjaman extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nip_peminjam',
        'jumlah',
        'tanggal_awal',
        'tanggal_akhir',
        'supir',
        'status'
    ];
    protected $table = 'peminjaman';

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(pegawai::class, 'nip_peminjam', 'nip');
    }

    public function detail_peminjaman(): HasMany
    {
        return $this->hasMany(detail_peminjaman::class, 'id_peminjaman', 'id');
    }
}
