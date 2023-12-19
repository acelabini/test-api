<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectDetail extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'number_of_units',
        'levels',
        'completion_date',
        'website',
        'phone',
        'mobile_number',
        'email',
        'sms_enable',
        'email_enable',
    ];

    protected $casts = [
        'completion_date'   =>  'datetime',
        'sms_enable'        =>  'bool',
        'email_enable'      =>  'bool',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
