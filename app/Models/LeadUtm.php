<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadUtm extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'source',
        'medium',
        'term',
        'content'
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }
}
