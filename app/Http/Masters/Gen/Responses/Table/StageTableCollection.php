<?php

namespace App\Http\Masters\Gen\Responses\Table;

use App\Http\Masters\Gen\Models\Stage;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Gen\Responses\Table\StageTableResponse;

class StageTableCollection extends ResourceCollection
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
            'columns' => Stage::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new StageTableResponse($data);
            })->all(),
            'filters' => Stage::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'geo/Stage/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'geo/Stage/reject'
                ]
            ],
        ];
    }
}
