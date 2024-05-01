<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kendaraan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'jenis_kendaraan',
        'tahun',
        'nopol',
        'warna',
        'status'
    ];
    protected $table = 'kendaraan';
}
