<?php

namespace App\Http\Resources\Projects;

use App\Traits\WithCacheService;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectPropertyResource extends JsonResource
{
    use WithCacheService;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'title'             =>  $this->title,
            'status'            =>  $this->status,
            'areaTotal'         =>  $this->area_total,
            'areaExternal'      =>  $this->area_external,
            'areaInternal'      =>  $this->area_internal,
            'bedrooms'          =>  $this->bedrooms,
            'bathrooms'         =>  $this->bathrooms,
            'carSpaces'         =>  $this->car_spaces,
            'levels'            =>  $this->levels,
            'price'             =>  number_format($this->price),
            'depositPayment'    =>  number_format($this->deposit_payment),
            'monthlyPayment'    =>  number_format($this->monthly_payment),
            $this->mergeWhen(show_resource('files'), function () {
            }),
            $this->mergeWhen(show_resource('thumbnails'), fn () => ['thumbnails' => $this->getThumbnails()]),
        ];
    }

    private function getThumbnails(): mixed
    {
        return self::$cache::remember(
            'projectProperty',
            "files-thumbnails-{$this->id}",
            fn () => preg_filter('/^/', config('byldan.cdn'), $this->thumbnails?->pluck('path')?->toArray()),
        );
    }
}
