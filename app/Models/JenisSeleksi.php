<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisSeleksi extends Model
{
    protected $table = "jenis_seleksi";

    protected $fillable = ['nama', 'kode'];

    public function tingkat()
    {
        return $this->belongsToMany(Tingkat::class, 'jenis_seleksi_tingkat')
            ->withPivot('urutan')
            ->withTimestamps()
            ->orderBy('pivot_urutan');
    }

    public function gelombang()
    {
        return $this->belongsToMany(Gelombang::class, 'gelombang_jenis_seleksi');
    }

}
