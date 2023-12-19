<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleCategory extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
