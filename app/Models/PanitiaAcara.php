<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanitiaAcara extends Model
{
    use HasFactory;

    protected $table = 'panitia_acara';
    protected $guarded = ['id'];

    /**
     * Relasi: Penugasan ini merujuk ke satu Pengguna (panitia).
     */
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    /**
     * Relasi: Penugasan ini merujuk ke satu Acara.
     */
    public function acara()
    {
        return $this->belongsTo(Acara::class, 'id_acara');
    }
}