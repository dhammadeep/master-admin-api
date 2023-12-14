<?php

namespace App\Http\Masters\Gen\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class MarketListResponse extends JsonResource
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
             'pincode' => $this->Location->pincode,
             'address' => $this->Location->address,
             'name' => $this->name,
            //  'is_benchmark' => $this->is_benchmark
        ];
    }
}
