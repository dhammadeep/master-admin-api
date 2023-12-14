<?php

namespace App\Http\Masters\Warehouse\Responses\Table;

use App\Http\Masters\Warehouse\Models\WarehouseType;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Warehouse\Responses\Table\WarehouseTypeTableResponse;

class WarehouseTypeTableCollection extends ResourceCollection
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
            'columns' => WarehouseType::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new WarehouseTypeTableResponse($data);
            })->all(),
            'filters' => WarehouseType::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'warehouse/warehouse-type/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'warehouse/warehouse-type/reject'
                ]
            ],
        ];
    }
}
