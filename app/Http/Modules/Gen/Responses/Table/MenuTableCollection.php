<?php

namespace App\Http\Modules\Gen\Responses\Table;

use App\Http\Modules\Gen\Models\Menu;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Modules\Gen\Responses\Table\MenuTableResponse;

class MenuTableCollection extends ResourceCollection
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
                return new MenuTableResponse($data);
            });
    }
}
