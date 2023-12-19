<?php

namespace App\Http\Resources\Projects;

use App\Traits\PaginateResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectCollection extends ResourceCollection
{
    use PaginateResource;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->paginate([
            'totalProjects' => $this->total(),
            'totalProperties' => (int) ($this->totalProperties ?? 0),
        ]);
    }
}
