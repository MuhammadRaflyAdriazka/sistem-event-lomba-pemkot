<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KolomFormulirAcara extends Model
{
    use HasFactory;

    protected $table = 'kolom_formulir_acara';
    protected $guarded = ['id'];

    /**
     * Relasi: Satu kolom formulir ini dimiliki oleh satu Acara.
     */
    public function acara()
    {
        return $this->belongsTo(Acara::class, 'id_acara');
    }
}