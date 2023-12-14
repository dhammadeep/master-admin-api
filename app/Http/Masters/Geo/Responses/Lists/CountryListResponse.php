<?php

namespace App\Http\Masters\Geo\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryListResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'iso2' => $this->iso2,
            'status' => $this->status
        ];
    }
}
