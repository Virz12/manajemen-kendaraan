<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class peminjaman extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'peminjaman';

    protected $fillable = [
        'id',
        'nip_peminjam',
        'jumlah',
        'tanggal_awal',
        'tanggal_akhir',
        'supir',
        'status'
    ];
    
    public static function booted() {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(pegawai::class, 'nip_peminjam', 'nip');
    }

    public function detail_peminjaman(): HasMany
    {
        return $this->hasMany(detail_peminjaman::class, 'id_peminjaman', 'id');
    }

    public function notification(): BelongsTo
    {
        return $this->belongsTo(notification::class, 'id', 'id_peminjaman');
    }
}
