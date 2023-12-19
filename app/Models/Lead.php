<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'lead_contact_id',
        'form_data',
        'type',
        'buy_within',
    ];

    public function leadContact(): BelongsTo
    {
        return $this->belongsTo(LeadContact::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
