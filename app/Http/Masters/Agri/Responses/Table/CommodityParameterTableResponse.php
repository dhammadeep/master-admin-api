<?php

namespace App\Http\Masters\Agri\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class CommodityParameterTableResponse extends JsonResource
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
            'commodity' => $this->Commodity->name ?? '',
            'phenophase' => $this->Phenophase->name ?? '',
            'parameterType' => $this->parameter_type ?? '',
            'parameter' => $this->Parameter->name ?? '',
            'status' => $this->status,
        ];
    }
}
