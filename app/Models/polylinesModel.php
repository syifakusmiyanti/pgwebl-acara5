<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class polylinesModel extends Model
{
    protected $table = 'polylines';
    // biar id ga berubah
    protected $guarded = ['id'];
}
