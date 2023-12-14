<?php

namespace App\Http\Masters\Agri\Responses\Table;

use App\Http\Masters\Agri\Models\CommodityModel;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Agri\Responses\Table\CommodityModelTableResponse;

class CommodityModelTableCollection extends ResourceCollection
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
            'columns' => CommodityModel::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new CommodityModelTableResponse($data);
            })->all(),
            'filters' => CommodityModel::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'agri/commodity-model/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'agri/commodity-model/reject'
                ]
            ],
        ];
    }
}
