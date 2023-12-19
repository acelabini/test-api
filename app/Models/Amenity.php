<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Amenity extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'label',
        'icon_class',
    ];
}
