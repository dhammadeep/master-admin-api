<?php

namespace App\Http\Masters\Menu\Responses\Table;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Menu\Responses\Table\ActivityMenuDropdownTableResponse;

class ActivityMenuDropdownTableCollection extends ResourceCollection
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
            return new ActivityMenuDropdownTableResponse($data);
        })->all();
    }
}
