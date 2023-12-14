<?php

namespace App\Http\Masters\Geo\Responses\Table;

use App\Http\Masters\Geo\Models\City;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Geo\Responses\Table\CityTableResponse;

class CityTableCollection extends ResourceCollection
{
    private $filter;

    public function __construct($resource, array $filter) {
        parent::__construct( $resource );
        $this->filter = $filter;
    }


    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'columns' => City::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new CityTableResponse($data);
            })->all(),
            'filters' => City::getFilterFields($this->filter),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'geo/city/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'geo/city/reject'
                ]
            ],
        ];
    }
}
