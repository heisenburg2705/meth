<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class KomponenBiaya extends Model
{
    use HasRoles, SoftDeletes;

    protected $table = 'komponen_biaya';

    // add guaded
    protected $guarded = ['id'];
    // add hidden
    protected $hidden = ['created_at', 'updated_at'];

    
    protected $casts = [
        'jenis_kelamin' => 'array',
    ];

    public function gelombang()
    {
        return $this->belongsToMany(Gelombang::class, 'komponen_biaya_gelombang');
    }

    public function jalur()
    {
        return $this->belongsToMany(Jalur::class, 'komponen_biaya_jalur');
    }

    public function subJalur()
    {
        return $this->belongsToMany(JalurDetail::class, 'komponen_biaya_jalur_detail');
    }

    public function tingkat()
    {
        return $this->belongsToMany(Tingkat::class, 'komponen_biaya_tingkat');
    }

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'komponen_biaya_kelas');
    }
}
