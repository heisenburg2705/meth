<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TahunAjaran extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'tahun_ajaran';
    protected $fillable = ['tahun_ajaran', 'semester', 'periode_mulai', 'periode_selesai', 'status'];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($tahunAjaran) {
            if ($tahunAjaran->status === 'aktif') {
                static::where('status', 'aktif')->update(['status' => 'non-aktif']);
            }
        });
    }

    protected static function booted(): void
    {
        static::saving(function ($tahunAjaran) {
            if ($tahunAjaran->status) {
                static::where('id', '!=', $tahunAjaran->id)->update(['status' => 0]);
            }
        });
    }
}
