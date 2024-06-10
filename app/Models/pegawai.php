<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class pegawai extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    protected $table = 'pegawai';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
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
}
