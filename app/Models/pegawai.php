<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class pegawai extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'pegawai';

    protected $fillable = [
        'id',
        'nip',
        'nama',
        'foto_profil',
        'status',
        'kelompok',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function booted() {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(peminjaman::class, 'nip_peminjam', 'nip');
    }

    public function detail_peminjaman(): BelongsTo
    {
        return $this->belongsTo(detail_peminjaman::class, 'id', 'id_supir');
    }

    public function detailpeminjaman(): BelongsTo
    {
        return $this->belongsTo(detail_peminjaman::class, 'id', 'id_pegawai');
    }

    public function notification(): HasMany
    {
        return $this->hasMany(notification::class, 'id_pegawai', 'id');
    }

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(kendaraan::class, 'id', 'id_supir');
    }
}
