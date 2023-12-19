<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends BaseModel
{
    use SoftDeletes;

    const PUBLISHED = 'published';
    const UNPUBLISHED = 'unpublished';
    const DRAFT = 'draft';

    const STATUSES = [
        self::PUBLISHED,
        self::UNPUBLISHED,
        self::DRAFT,
    ];

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'content',
        'status',
        'published_at',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_articles');
    }

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'article_files');
    }

    public function thumbnails(): BelongsToMany
    {
        return $this->files()->where([
            'type'      =>  File::IMAGE,
            'sub_type'  =>  File::THUMBNAIL,
        ]);
    }
}
