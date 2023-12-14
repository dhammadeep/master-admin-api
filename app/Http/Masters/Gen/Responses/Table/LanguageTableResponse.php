<?php

namespace App\Http\Masters\Gen\Responses\Table;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class LanguageTableResponse extends JsonResource
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
            'languageCode' => $this->code,
            'logo' => Str::replace('/dev/master-data/gen-language/', '/dev/master-data/gen-language/thumbnails/', $this->logo),
            'fileUrl' => $this->file_url,
            'status' => $this->status
        ];
    }
}
