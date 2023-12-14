<?php

namespace App\Http\Masters\Test\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class ExampleListResource extends JsonResource
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
            'Name' => $this->Name,
            'AboutMe' => $this->AboutMe,
            'CountryID' => $this->CountryID,
            'StateID' => $this->StateID,
            'ProfilePhoto' => $this->ProfilePhoto,
            'Gender' => $this->Gender
        ];
    }
}
