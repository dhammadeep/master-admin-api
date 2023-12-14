<?php

namespace App\Http\Masters\Gen\Responses\Lists;

use Illuminate\Http\Resources\Json\JsonResource;

class LanguageListResponse extends JsonResource
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
            'code' => $this->code,
            'logo' => $this->logo,
            'file_url' => $this->file_url
        ];
    }
}
