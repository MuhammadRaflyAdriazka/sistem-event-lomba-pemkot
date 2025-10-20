<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Acara extends Model
{
    use HasFactory;
    protected $table = 'acara';
    protected $guarded = ['id'];

    // Casting tanggal
    protected $casts = [
        'tanggal_acara' => 'date',
        'tanggal_mulai_daftar' => 'date',
        'tanggal_akhir_daftar' => 'date',
    ];

    // Accessor untuk compatibility dengan template
    public function getTitleAttribute()
    {
        return $this->judul;
    }

    public function getImageAttribute()
    {
        return $this->gambar;
    }

    public function getFormFieldsAttribute()
    {
        return $this->kolomFormulir->map(function ($field) {
            return (object) [
                'field_label' => $field->label_kolom,
                'field_name' => $field->nama_kolom,
                'field_type' => $field->tipe_kolom,
                'is_required' => $field->wajib_diisi,
                'placeholder' => $field->placeholder,
            ];
        });
    }

    // Relasi: Acara ini dimiliki oleh satu Dinas
    public function dinas()
    {
        return $this->belongsTo(Dinas::class, 'id_dinas');
    }

    // Relasi: Acara ini memiliki banyak kolom formulir
    public function kolomFormulir()
    {
        return $this->hasMany(KolomFormulirAcara::class, 'id_acara');
    }

    // Relasi: Acara ini memiliki banyak Pendaftaran
    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'id_acara');
    }
    
    // Relasi: Acara ini memiliki banyak panitia (User)
    public function panitia()
    {
        return $this->belongsToMany(User::class, 'panitia_acara', 'id_acara', 'id_pengguna');
    }
}
