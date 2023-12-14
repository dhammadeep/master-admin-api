<?php

namespace App\Http\Masters\Agri\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class CommodityListResponse extends JsonResource
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
            'logo' => $this->logo ?? '',
            'forward_ready' => $this->forward_ready ? 1:0,
            'warehouse_ready' => $this->warehouse_ready ? 1:0,
            'payment_service_ready' => $this->payment_service_ready ? 1:0
        ];
    }
}
