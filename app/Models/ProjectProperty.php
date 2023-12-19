<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectProperty extends BaseModel
{
    use SoftDeletes;

    const PUBLISHED = 'published';
    const PRIVATE = 'private';
    const UNPUBLISHED = 'unpublished';
    const ARCHIVED = 'archived';

    const STATUSES = [
        self::PUBLISHED,
        self::PRIVATE,
        self::UNPUBLISHED,
        self::ARCHIVED,
    ];

    protected $fillable = [
        'project_id',
        'title',
        'status',
        'area_total',
        'area_external',
        'area_internal',
        'bedrooms',
        'bathrooms',
        'car_spaces',
        'levels',
        'price',
        'deposit_payment',
        'monthly_payment',
    ];

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'project_property_files');
    }

    public function thumbnails(): BelongsToMany
    {
        return $this->files()->where([
            'type'      =>  File::IMAGE,
            'sub_type'  =>  File::THUMBNAIL,
        ]);
    }
}
