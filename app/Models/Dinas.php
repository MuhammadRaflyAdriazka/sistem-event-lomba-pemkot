<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dinas extends Model
{
     use HasFactory;
    protected $table = 'dinas';
    protected $guarded = ['id'];

    // Relasi: Satu Dinas memiliki banyak User
    public function users()
    {
        return $this->hasMany(User::class, 'id_dinas');
    }

    // Relasi: Satu Dinas menyelenggarakan banyak Acara
    public function acara()
    {
        return $this->hasMany(Acara::class, 'id_dinas');
    }
}
