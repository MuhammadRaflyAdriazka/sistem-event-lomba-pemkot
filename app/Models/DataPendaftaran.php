<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPendaftaran extends Model
{
    use HasFactory;

    protected $table = 'data_pendaftaran';
    protected $guarded = ['id'];

    /**
     * Relasi: Satu data isian ini dimiliki oleh satu Pendaftaran.
     */
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran');
    }
}