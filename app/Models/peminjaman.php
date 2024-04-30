<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class peminjaman extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nip_peminjam',
        'jumlah',
        'tanggal_awal',
        'tanggal_akhir',
        'status'
    ];
    protected $table = 'peminjaman';
}
