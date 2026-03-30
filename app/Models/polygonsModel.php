<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class polygonsModel extends Model
{
    protected $table = 'polygons';
    // biar id ga berubah
    protected $guarded = ['id'];
}
