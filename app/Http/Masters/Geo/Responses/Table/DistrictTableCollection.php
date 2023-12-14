<?php

namespace App\Http\Masters\Geo\Responses\Table;

use App\Http\Masters\Geo\Models\District;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Geo\Responses\Table\DistrictTableResponse;

class DistrictTableCollection extends ResourceCollection
{

    private $statefilter;

    public function __construct($resource, array $statefilter) {
        parent::__construct( $resource );
        $this->statefilter = $statefilter;
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
            'columns' => District::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new DistrictTableResponse($data);
            })->all(),
            'filters' => District::getFilterFields($this->statefilter),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'geo/district/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'geo/district/reject'
                ]
            ],
        ];
    }
}
