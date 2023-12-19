<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'           => $this->id,
            'firstName'    => $this->first_name,
            'middleName'   => $this->middle_name,
            'lastName'     => $this->last_name,
            'email'        => $this->email,
            'mobilePrefix' => $this->mobile_prefix,
            'mobileNumber' => $this->mobile_number,
            'verifiedAt'   => $this->verified_at
        ];
    }
}
