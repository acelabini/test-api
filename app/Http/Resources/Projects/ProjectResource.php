<?php

namespace App\Http\Resources\Projects;

use App\Http\Resources\AddressResource;
use App\Http\Resources\AmenityResource;
use App\Http\Resources\UserResource;
use App\Traits\ProjectBuilder;
use App\Traits\WithCacheService;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    use WithCacheService;
    use ProjectBuilder;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                =>  $this->id,
            'title'             =>  $this->title,
            'slug'              =>  $this->slug,
            'status'            =>  $this->status,
            'projectStatus'     =>  $this->project_status,
            'featuredWeight'    =>  $this->featured_weight,
            'type'              =>  $this->type,
            'projectStart'      =>  $this->project_start_at->format('Y-m-d'),
            'projectEnd'        =>  $this->project_end_at->format('Y-m-d'),
            // Include description of the project in the response
            $this->mergeWhen(show_resource('description'), fn () => ['description' => $this->description]),
            // Include property spaces such as bedrooms
            $this->mergeWhen(show_resource('spaces'), fn () => $this->getSpaces()),
            $this->mergeWhen(show_resource('prices'), fn () => $this->getPrices()),
            $this->mergeWhen(
                show_resource('owner'),
                fn () => ['owner' => self::$cache::resource(UserResource::class, $this->user)]
            ),
            $this->mergeWhen(
                show_resource('properties'),
                fn () => ['properties' => self::$cache::resource(
                    ProjectPropertyResource::class,
                    $this->properties,
                    $this->id
                )]
            ),
            $this->mergeWhen(
                show_resource('address'),
                fn () => ['address' => self::$cache::resource(AddressResource::class, $this->address)]
            ),
            $this->mergeWhen(
                show_resource('details'),
                fn () => ['details' => self::$cache::resource(PropertyDetailResource::class, $this->details)]
            ),
            $this->mergeWhen(
                show_resource('amenities'),
                fn () => ['amenities' => self::$cache::resource(AmenityResource::class, $this->amenities)]
            ),
            $this->mergeWhen(show_resource('files'), function () {
            }),
            $this->mergeWhen(show_resource('thumbnails'), fn () => ['thumbnails' => $this->getThumbnails()]),
            $this->mergeWhen(
                show_resource('unit_images'),
                fn () => ['properties' => self::$cache::resource(
                    ProjectPropertyResource::class,
                    $this->properties,
                    $this->id
                )]
            ),
        ];
    }

    private function getThumbnails(): mixed
    {
        return self::$cache::remember(
            'project',
            "files-thumbnails-{$this->id}",
            fn () => preg_filter('/^/', config('byldan.cdn'), $this->thumbnails?->pluck('path')?->toArray()),
        );
    }

    private function getSpaces(): mixed
    {
        return self::$cache::remember(
            'project',
            "spaces-{$this->id}",
            fn () => self::buildSpaces($this->properties),
        );
    }

    private function getPrices(): mixed
    {
        return self::$cache::remember(
            'project',
            "prices-{$this->id}",
            fn () => self::buildPrices($this->properties),
        );
    }
}
