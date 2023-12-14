<?php

namespace App\Http\Masters\Agri\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class QualityParameterRangeListResponse extends JsonResource
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
            'commodity_id' => $this->commodity_id,
            'parameter_id' =>  $this->parameter_id,
            'quality_band_id' => $this->quality_band_id,
            'min_value' => $this->min_value,
            'max_value' => $this->max_value
        ];
    }
}
