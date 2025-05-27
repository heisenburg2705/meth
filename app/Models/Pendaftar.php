<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    protected $table = 'pendaftar';

    // add fillable
    protected $fillable = ['nama_lengkap', 'email', 'password', 'no_telepon', 'alamat', 'tanggal_lahir', 'jenis_kelamin', 'asal_sekolah', 'orang_tua_id'];
    // add guaded
    protected $guarded = ['id'];
    // add hidden
    protected $hidden = ['created_at', 'updated_at'];

    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class, 'orang_tua_id');
    }
}
