<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class detail_peminjaman extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'detail_peminjaman';

    protected $fillable = [
        'id',
        'nopol',
        'id_peminjaman',
        'id_pegawai',
        'id_supir'
    ];

    public static function booted() {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }    

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(peminjaman::class, 'id_peminjaman', 'id');
    }

    public function kendaraan(): HasMany
    {
        return $this->hasMany(kendaraan::class, 'nopol', 'nopol');
    }

    public function supir(): HasMany
    {
        return $this->hasMany(pegawai::class, 'id', 'id_supir');
    }

    public function tim_kendaraan(): HasOne
    {
        return $this->hasOne(pegawai::class, 'id', 'id_pegawai');
    }
}
