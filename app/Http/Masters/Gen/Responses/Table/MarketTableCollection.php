<?php

namespace App\Http\Masters\Gen\Responses\Table;

use App\Http\Masters\Gen\Models\Market;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Gen\Responses\Table\MarketTableResponse;

class MarketTableCollection extends ResourceCollection
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
            'columns' => Market::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new MarketTableResponse($data);
            })->all(),
            'filters' => Market::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'gen/market/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'gen/market/reject'
                ]
            ],
        ];
    }
}
