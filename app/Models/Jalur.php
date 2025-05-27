<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use App\Models\JalurDetail;

class Jalur extends Model
{
    use HasRoles;

    protected $table = 'jalur';
    // add fillable
    protected $fillable = ['nama', 'status'];
    // add guaded
    protected $guarded = ['id'];
    // add hidden
    protected $hidden = ['created_at', 'updated_at'];

    public function details()
    {
        return $this->hasMany(JalurDetail::class, 'jalur_id', 'id');
    }
}
