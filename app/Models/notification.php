<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'id_pegawai',
        'notification',
    ];
    protected $table = 'notification';

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(pegawai::class, 'id_pegawai', 'id');
    }
}
