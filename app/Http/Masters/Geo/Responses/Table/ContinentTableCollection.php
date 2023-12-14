<?php

namespace App\Http\Masters\Geo\Responses\Table;

use App\Http\Masters\Geo\Models\Continent;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Geo\Responses\Table\ContinentTableResponse;

class ContinentTableCollection extends ResourceCollection
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
            'columns' => Continent::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new ContinentTableResponse($data);
            })->all(),
            'filters' => Continent::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'geo/Continent/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'geo/Continent/reject'
                ]
            ],
        ];
    }
}
