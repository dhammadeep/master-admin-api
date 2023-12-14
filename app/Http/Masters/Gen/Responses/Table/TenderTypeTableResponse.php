<?php

namespace App\Http\Masters\Gen\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class TenderTypeTableResponse extends JsonResource
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
            'status' => $this->status,
        ];
    }
}
