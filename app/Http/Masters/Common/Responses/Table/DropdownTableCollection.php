<?php

namespace App\Http\Masters\Common\Responses\Table;

use App\Http\Masters\Common\Models\Dropdown;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Common\Responses\Table\DropdownTableResponse;

class DropdownTableCollection extends ResourceCollection
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
            return new DropdownTableResponse($data);
        })->all();
    }
}
