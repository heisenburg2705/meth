<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Syarat extends Model
{
    use HasRoles;
    protected $table = 'syarat';

    // add fillable
    protected $fillable = ['nama', 'kode'];
    // add guaded
    protected $guarded = ['id'];
    // add hidden
    protected $hidden = ['created_at', 'updated_at'];

    public function tingkat()
    {
        return $this->belongsToMany(Tingkat::class, 'syarat_tingkat_select')
            ->withPivot('syarat_id', 'tingkat_id')
            ->withTimestamps();
    }
}
