<?php

namespace App\Http\Masters\Agri\Responses\Table;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class CommodityTableResponse extends JsonResource
{
     /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // dd($this);
         return [
            'id' => $this->id,
            'name' => $this->name,
            'logo' => Str::replace('/dev/master-data/agri-commodity/', '/dev/master-data/agri-commodity/thumbnails/', $this->logo),
            'forwardReady' => ($this->forward_ready) ? 'Yes':'No',
            'warehouseReady' => ($this->warehouse_ready) ? 'Yes':'No',
            'paymentService' => ($this->payment_service_ready) ? 'Yes':'No',
            'status' => $this->status
        ];
    }
}
