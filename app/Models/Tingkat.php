<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Iluminate\Database\Eloquent\Relations\HasMany;

class Tingkat extends Model
{
    use HasRoles;

    protected $table = "tingkat";
    protected $fillable = ['nama', 'singkatan', 'kode_formulir', 'kode_tingkat', 'kuota', 'logo'];
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function getLogoMediaUrl()
    {
        return $this->logo ? Storage::url($this->logo) : null;
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'tingkat_id');
    }

    public function jenisSeleksi()
    {
        return $this->belongsToMany(JenisSeleksi::class, 'jenis_seleksi_tingkat')
            ->withPivot('urutan')
            ->withTimestamps();
    }

    public function syarat()
    {
        return $this->belongsToMany(Syarat::class, 'syarat_tingkat_select')
            ->withPivot('syarat_id', 'tingkat_id')
            ->withTimestamps();
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($tingkat) {
            if ($tingkat->kelas()->exists()) {
                throw new \Exception("Tingkat ini tidak dapat dihapus karena masih memiliki Data Master Kelas yang terkait.");
            }

            if ($tingkat->jenisSeleksi()->exists()) {
                throw new \Exception("Tingkat ini tidak dapat dihapus karena masih memiliki Data Master Jenis Seleksi yang terkait.");
            }

            if ($tingkat->syarat()->exists()) {
                throw new \Exception("Tingkat ini tidak dapat dihapus karena masih memiliki Data Master Syarat yang terkait.");
            }
        });
    }
}
