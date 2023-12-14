<?php

namespace App\Http\Masters\Gen\Responses\Table;

use App\Http\Masters\Gen\Models\TenderType;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Gen\Responses\Table\TenderTypeTableResponse;

class TenderTypeTableCollection extends ResourceCollection
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
            'columns' => TenderType::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new TenderTypeTableResponse($data);
            })->all(),
            'filters' => TenderType::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'geo/TenderType/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'geo/TenderType/reject'
                ]
            ],
        ];
    }
}
