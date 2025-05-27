<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Gelombang extends Model
{
    use HasRoles, SoftDeletes;

    protected $table = 'gelombang';

    // add fillable
    protected $fillable = [
        'tahun_ajaran_id',
        'tingkat_id',
        'nama',
        'kode',
        'tanggal_awal',
        'tanggal_akhir',
        'registrasi_ulang_awal',
        'registrasi_ulang_akhir',
        'tanggal_awal_seleksi',
        'tanggal_akhir_seleksi',
        'tanggal_pengumuman',
    ];
    
    // add guaded
    protected $guarded = ['id'];
    // add hidden
    protected $hidden = ['created_at', 'updated_at'];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function tingkat()
    {
        return $this->belongsTo(Tingkat::class);
    }

    public function jenisSeleksi()
    {
        return $this->belongsToMany(JenisSeleksi::class, 'gelombang_jenis_seleksi');
    }

    public function komponenBiaya()
    {
        return $this->belongsToMany(KomponenBiaya::class, 'komponen_biaya_gelombang');
    }
}
