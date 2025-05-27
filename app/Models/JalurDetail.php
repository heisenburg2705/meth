<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class JalurDetail extends Model
{
    use HasRoles;

    protected $table = 'jalur_detail';

    // add fillable
    protected $fillable = ['nama', 'jalur_id'];
    // add guaded
    protected $guarded = ['id'];
    // add hidden
    protected $hidden = ['created_at', 'updated_at'];
}
