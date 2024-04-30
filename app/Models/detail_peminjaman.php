<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detail_peminjaman extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nopol',
        'id_pegawai',
        'id_supir'
    ];
    protected $table = 'detail_peminjaman';
}
