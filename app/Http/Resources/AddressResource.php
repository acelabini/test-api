<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                    =>  $this->id,
            'thoroughfareNumber'    =>  $this->thoroughfare_number ?: '',
            'thoroughfareName'      =>  $this->thoroughfare_name ?: '',
            'street'                =>  $this->street ?: '',
            'city'                  =>  $this->city,
            'community'             =>  $this->community ?: '',
            'province'              =>  $this->province,
            'postalCode'            =>  $this->postal_code,
            'longitude'             =>  $this->longitude,
            'latitude'              =>  $this->latitude,
        ];
    }
}
