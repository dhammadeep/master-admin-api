<?php

namespace App\Http\Masters\Geo\Responses\Table;

use App\Http\Masters\Geo\Models\Location;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Geo\Responses\Table\LocationTableResponse;

class LocationTableCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'columns' => Location::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new LocationTableResponse($data);
            })->all(),
            'filters' => Location::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'geo/Location/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'geo/Location/reject'
                ]
            ],
        ];
    }
}
