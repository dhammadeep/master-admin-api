<?php

namespace App\Http\Masters\Geo\Responses\Table;

use App\Http\Masters\Geo\Models\Region;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Geo\Responses\Table\RegionTableResponse;

class RegionTableCollection extends ResourceCollection
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
            'columns' => Region::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new RegionTableResponse($data);
            })->all(),
            'filters' => Region::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'geo/Region/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'geo/Region/reject'
                ]
            ],
        ];
    }
}
