<?php

namespace App\Http\Masters\Gen\Responses\Table;

use App\Http\Masters\Gen\Models\UnitOfMeasurement;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Gen\Responses\Table\UnitOfMeasurementTableResponse;

class UnitOfMeasurementTableCollection extends ResourceCollection
{


    private $uomTypefilter;

    public function __construct($resource, array $uomTypefilter) {
        parent::__construct( $resource );
        $this->uomTypefilter = $uomTypefilter;
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
            'columns' => UnitOfMeasurement::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new UnitOfMeasurementTableResponse($data);
            })->all(),
            'filters' => UnitOfMeasurement::getFilterFields($this->uomTypefilter),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'geo/UnitOfMeasurement/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'geo/UnitOfMeasurement/reject'
                ]
            ],
        ];
    }
}
