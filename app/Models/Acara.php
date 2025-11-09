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

    /**
     * Mendapatkan jumlah peserta yang sudah diterima (untuk sistem tanpa seleksi)
     * atau jumlah peserta yang sudah disetujui (untuk sistem seleksi)
     */
    public function getJumlahPesertaDiterimaAttribute()
    {
        return $this->pendaftaran()
                    ->where('status', 'disetujui')
                    ->count();
    }

    /**
     * Mendapatkan sisa kuota yang tersedia
     */
    public function getSisaKuotaAttribute()
    {
        return max(0, $this->kuota - $this->jumlah_peserta_diterima);
    }

    /**
     * Mengecek apakah kuota sudah penuh (khusus untuk sistem tanpa seleksi)
     */
    public function getIsKuotaPenuhAttribute()
    {
        if ($this->sistem_pendaftaran === 'Tanpa Seleksi') {
            return $this->jumlah_peserta_diterima >= $this->kuota;
        }
        
        // Untuk sistem seleksi, kuota tidak membatasi pendaftaran
        return false;
    }

    /**
     * Mengecek apakah pendaftaran masih bisa dilakukan
     */
    public function getCanRegisterAttribute()
    {
        $sekarang = now();
        
        // Cek periode pendaftaran
        if (!$sekarang->between($this->tanggal_mulai_daftar, $this->tanggal_akhir_daftar)) {
            return false;
        }
        
        // Cek status acara
        if ($this->status !== 'active') {
            return false;
        }
        
        // Khusus sistem tanpa seleksi, cek kuota
        if ($this->sistem_pendaftaran === 'Tanpa Seleksi') {
            return !$this->is_kuota_penuh;
        }
        
        return true;
    }
}
