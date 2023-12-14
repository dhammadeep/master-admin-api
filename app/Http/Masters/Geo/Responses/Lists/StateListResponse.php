<?php

namespace App\Http\Masters\Geo\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class StateListResponse extends JsonResource
{
     /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         return [
             'name' => $this->name,
             'country_id' => $this->country_id
        ];
    }
}
