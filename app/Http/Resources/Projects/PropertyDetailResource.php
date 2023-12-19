<?php

namespace App\Http\Resources\Projects;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyDetailResource extends JsonResource
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
            'numberOfUnits'     =>  $this->number_of_units,
            'levels'            =>  $this->levels,
            'completionDate'    =>  $this->completion_date?->format('Y-m-d'),
            'website'           =>  $this->website,
            'phone'             =>  $this->phone,
            'mobileNumber'      =>  $this->mobile_number,
            'email'             =>  $this->email,
            'smsEnable'         =>  $this->sms_enable,
            'emailEnable'       =>  $this->email_enable,
        ];
    }
}
