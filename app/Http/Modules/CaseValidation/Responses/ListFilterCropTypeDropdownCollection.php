<?php

namespace App\Http\Modules\CaseValidation\Responses;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Modules\CaseValidation\Responses\ListFilterCropTypeDropdownResponse;

class ListFilterCropTypeDropdownCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($data) {
            return new ListFilterCropTypeDropdownResponse($data);
        })->all();
    }
}
