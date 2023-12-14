<?php

namespace App\Http\Masters\Gen\Responses\Table;

use App\Http\Masters\Gen\Models\UnitOfMeasurementType;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Gen\Responses\Table\UnitOfMeasurementTypeTableResponse;

class UnitOfMeasurementTypeTableCollection extends ResourceCollection
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
            'columns' => UnitOfMeasurementType::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new UnitOfMeasurementTypeTableResponse($data);
            })->all(),
            'filters' => UnitOfMeasurementType::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'geo/UnitOfMeasurementType/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'geo/UnitOfMeasurementType/reject'
                ]
            ],
        ];
    }
}
