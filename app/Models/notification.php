<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class notification extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'notification';

    protected $fillable = [
        'id',
        'id_pegawai',
        'id_peminjaman',
        'notification',
    ];
    
    public static function booted() {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(pegawai::class, 'id_pegawai', 'id');
    }

    public function peminjaman(): HasOne
    {
        return $this->hasOne(peminjaman::class, 'id', 'id_peminjaman');
    } 
}
