<?php

namespace App\Http\Masters\Geo\Responses\Table;

use App\Http\Masters\Geo\Models\Country;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Geo\Responses\Table\CountryTableResponse;

class CountryTableCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'columns' => Country::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new CountryTableResponse($data);
            })->all(),
            'filters' => Country::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'geo/country/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'geo/country/reject'
                ]
            ],
        ];
    }
}
