<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Laravel\Scout\Searchable;
use Laravel\Scout\Builder as ScoutBuilder;

class Project extends BaseModel
{
    use SoftDeletes;
    use Searchable;
    use ScoutFilters;

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

    const CONDO = 'condominiums';
    const TOWNHOUSE = 'townhouses';
    const LAND_STATE = 'land';
    const HOUSE_LOT = 'house-and-lot';

    const TYPES = [
        self::CONDO,
        self::TOWNHOUSE,
        self::LAND_STATE,
        self::HOUSE_LOT,
    ];

    const PRE_SELLING = 'pre-selling';
    const READY_FOR_OCCUPANCY = 'ready-for-occupancy';

    const PROJECT_STATUSES = [
        self::PRE_SELLING,
        self::READY_FOR_OCCUPANCY,
    ];

    protected $with = [
        'properties'
    ];

    protected $fillable = [
        'user_id',
        'address_id',
        'title',
        'slug',
        'featured_weight',
        'status',
        'project_status',
        'description',
        'type',
        'project_start_at',
        'project_end_at',
        'bci_id',
    ];

    protected $casts = [
        'project_start_at'  =>  'datetime:Y-m-d',
        'project_end_at'    =>  'datetime:Y-m-d',
    ];

    protected static $searchable = [
        'title',
        'address',
    ];

    protected static $filterable = [
        '_geo'              =>  '=',
        'type'              =>  '=',
        'postal_code'       =>  '=',
        'project_status'    =>  '=',
        'min_bedroom'       =>  '>=',
        'max_bedroom'       =>  '<=',
        'min_bathrooms'     =>  '>=',
        'max_bathrooms'     =>  '<=',
        'min_car_spaces'    =>  '>=',
        'max_car_spaces'    =>  '<=',
        'min_price'         =>  '>=',
        'max_price'         =>  '<=',
    ];

    protected static $sortable = [
        'featured_weight' => 'desc',
    ];

    public function shouldBeSearchable(): bool
    {
        return $this->status === self::PUBLISHED;
    }

    public function toSearchableArray(): array
    {
        return [
            'title'                 =>  $this->title,
            'featured_weight'       =>  $this->featured_weight,
            'type'                  =>  $this->type,
            'project_status'        =>  $this->project_status,
            'address'               =>  $this->address->thoroughfare_name . " " . $this->city,
            'postal_code'           =>  $this->address->postal_code,
            'min_bedroom'           =>  (int) $this->properties()->min('bedrooms'),
            'max_bedroom'           =>  (int) $this->properties()->max('bedrooms'),
            'min_bathrooms'         =>  (int) $this->properties()->min('bathrooms'),
            'max_bathrooms'         =>  (int) $this->properties()->max('bathrooms'),
            'min_car_spaces'        =>  (int) $this->properties()->min('car_spaces'),
            'max_car_spaces'        =>  (int) $this->properties()->max('car_spaces'),
            'min_price'             =>  (float) $this->properties()
                ->where('price', '>', 0)->min('price'),
            'max_price'             =>  (float) $this->properties()->max('price'),
            '_geo'                  =>  [
                'lat'   =>  (float) $this->address->latitude,
                'lng'   =>  (float) $this->address->longitude
            ]
        ];
    }

    public static function withFilters(array $filters = [], array $with = [])
    {
        return self::filters(self::query(), $filters, $with);
    }

    public static function filters($query, array $filters = [], array $with = []): Builder
    {
        return $query
            ->when(is_true(Arr::get($with, 'owner')), function (Builder $q) use ($filters) {
                $q->with('user');
            })
            ->when(is_true(Arr::get($with, 'details')), function (Builder $q) use ($filters) {
                $q->with('details');
            })
            ->when(is_true(Arr::get($with, 'address')), function (Builder $q) use ($filters) {
                $q->with('address');
            })
            ->when(is_true(Arr::get($with, 'properties')), function (Builder $q) use ($filters) {
                $q->with('properties');
            })
            ->when(is_true(Arr::get($with, 'files')), function (Builder $q) use ($filters) {
                $q->with('files');
            })
            ->when(Arr::get($filters, 'status'), function (Builder $q) use ($filters) {
                $q->where('status', $filters['status']);
            })
            ->when(Arr::get($filters, 'projectStatus'), function (Builder $q) use ($filters) {
                $q->where('project_status', $filters['projectStatus']);
            })
            ->when(is_true(Arr::get($filters, 'featured')), function (Builder $q) use ($filters) {
                $q->orderBy('featured_weight', 'desc');
            })
            ->when(Arr::get($filters, 'orderBy'), function (Builder $q) use ($filters) {
                $q->orderBy($filters['orderBy'], $filters['ordering'] ?? 'desc');
            });
    }

    public static function totalUnits(Builder $query): int
    {
        return $query
            ->join('project_details', 'project_details.project_id', 'projects.id')
            ->select('number_of_units')
            ->sum('number_of_units');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasOne
    {
        return $this->hasOne(ProjectDetail::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(ProjectProperty::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'project_amenities');
    }

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'project_files');
    }

    public function thumbnails(): BelongsToMany
    {
        return $this->files()->where([
            'type'      =>  File::IMAGE,
            'sub_type'  =>  File::THUMBNAIL,
        ]);
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'project_articles');
    }
}
