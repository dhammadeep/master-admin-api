<?php

namespace App\Http\Masters\Gen\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class MarketTableResponse extends JsonResource
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
            'name' => $this->name,
            'pincode' => $this->Location->pincode ?? '',
            'address' => $this->Location->address ?? '',
            // 'benchmark' => ($this->is_benchmark) ? 'Yes':'No',
            'status' => $this->status,
        ];
    }
}
