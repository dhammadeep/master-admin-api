<?php

namespace App\Http\Masters\Agri\Responses\Table;

use App\Http\Masters\Agri\Models\Commodity;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Agri\Responses\Table\CommodityTableResponse;

class CommodityTableCollection extends ResourceCollection
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
            'columns' => Commodity::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new CommodityTableResponse($data);
            })->all(),
            'filters' => Commodity::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'agri/commodity/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'agri/commodity/reject'
                ]
            ],
        ];
    }
}
