<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';
    protected $guarded = ['id'];

    /**
     * Relasi: Pengumuman ini dimiliki oleh satu Acara.
     */
    public function acara()
    {
        return $this->belongsTo(Acara::class, 'id_acara');
    }

    /**
     * Relasi: Pengumuman ini dibuat oleh (dimiliki oleh) satu Pengguna.
     */
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }
}