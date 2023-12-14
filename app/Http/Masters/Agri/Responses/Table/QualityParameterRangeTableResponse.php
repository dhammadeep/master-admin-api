<?php

namespace App\Http\Masters\Agri\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class QualityParameterRangeTableResponse extends JsonResource
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
            'id' => $this->id,
            'commodity' => $this->Commodity->name ?? "",
            'parameter' => $this->Parameter->name ?? "",
            'quality' => $this->Quality->name ?? "",
            'minValue' => $this->min_value ?? "",
            'maxValue' => $this->max_value ?? "",
            'status' => $this->status,
        ];
    }
}
