<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pendaftaran extends Model
{
     use HasFactory;
    protected $table = 'pendaftaran';
    protected $guarded = ['id'];

    // Relasi: Pendaftaran ini milik satu Acara
    public function acara()
    {
        return $this->belongsTo(Acara::class, 'id_acara');
    }

    // Relasi: Pendaftaran ini milik satu User (Peserta)
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    // Relasi: Pendaftaran ini memiliki banyak data isian form
    public function dataPendaftaran()
    {
        return $this->hasMany(DataPendaftaran::class, 'id_pendaftaran');
    }
}
