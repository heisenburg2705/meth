<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class OrangTua extends Authenticatable
{
    protected $table = 'orang_tua';

    // add fillable
    protected $fillable = ['nama_lengkap', 'email', 'password', 'no_telepon'];
    // add guaded
    protected $guarded = ['id'];
    // add hidden
    protected $hidden = ['created_at', 'updated_at'];

    public function pendaftar()
    {
        return $this->hasMany(Pendaftar::class, 'orang_tua_id');
    }
}
