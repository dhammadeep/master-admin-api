<?php

namespace App\Http\Masters\Geo\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class RegionTableResponse extends JsonResource
{
     /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $location = [
            $this->latitude_min,
            $this->latitude_max,
            $this->longitude_min,
            $this->longitude_max
        ];
         return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => $location,
            // 'latitudeMin' => $this->latitude_min,
            // 'latitudeMax' => $this->latitude_max,
            // 'longitudeMin' => $this->longitude_min,
            // 'longitudeMax' => $this->longitude_max,
            'status' => $this->status,
        ];
    }
}
