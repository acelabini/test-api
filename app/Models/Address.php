<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'thoroughfare_number',
        'thoroughfare_name',
        'street',
        'city',
        'province',
        'postal_code',
        'longitude',
        'latitude',
    ];
}
