<?php

namespace App\Http\Masters\Agri\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class CommodityParameterListResponse extends JsonResource
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
             'phenophase_id' => $this->phenophase_id,
             'parameter_type' => $this->parameter_type,
             'parameter_id' => $this->parameter_id
        ];
    }
}
