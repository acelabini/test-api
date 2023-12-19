<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class File extends BaseModel
{
    use SoftDeletes;

    const UPDATED_AT = null;

    public const IMAGE = 'image';
    public const PDF = 'pdf';

    public const TYPES = [
        self::IMAGE,
        self::PDF,
    ];

    public const THUMBNAIL = 'thumbnail';
    public const HERO = 'hero';

    public const SUB_TYPES = [
        self::THUMBNAIL,
        self::HERO,
    ];

    protected $fillable = [
        'type',
        'sub_type',
        'name',
        'path',
    ];
}
