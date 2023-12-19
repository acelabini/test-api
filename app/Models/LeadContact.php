<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadContact extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'full_name',
        'mobile',
        'email'
    ];
}
