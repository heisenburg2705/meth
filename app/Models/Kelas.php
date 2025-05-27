<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    //

    // add fillable
    protected $fillable = ['nama', 'kode', 'tingkat_id'];
    // add guaded
    protected $guarded = ['id'];
    // add hidden
    protected $hidden = ['created_at', 'updated_at'];

    public function tingkat()
    {
        return $this->belongsTo(Tingkat::class, 'tingkat_id');
    }
}
