<?php

namespace App\Http\Masters\Geo\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class RegionListResponse extends JsonResource
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
            'latitude_min' => $this->latitude_min,
            'latitude_max' => $this->latitude_max,
            'longitude_min' => $this->longitude_min,
            'longitude_max' => $this->longitude_max
        ];
    }
}
