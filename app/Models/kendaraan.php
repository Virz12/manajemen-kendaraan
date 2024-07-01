<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class kendaraan extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'kendaraan';

    protected $fillable = [
        'id',
        'jenis_kendaraan',
        'id_supir',
        'tahun',
        'nopol',
        'warna',
        'foto_kendaraan',
        'kondisi',
        'status',
    ];
    
    public static function booted() {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function detail_peminjaman(): BelongsTo
    {
        return $this->belongsTo(detail_peminjaman::class, 'nopol', 'nopol');
    }

    public function supir(): HasOne
    {
        return $this->hasOne(pegawai::class, 'id', 'id_supir');
    }
}
