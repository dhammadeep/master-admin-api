<?php

namespace App\Http\Masters\Agri\Responses\Table;

use App\Http\Masters\Agri\Models\Quality;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Agri\Responses\Table\QualityTableResponse;

class QualityTableCollection extends ResourceCollection
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
            'columns' => Quality::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new QualityTableResponse($data);
            })->all(),
            'filters' => Quality::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'agri/quality/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'agri/quality/reject'
                ]
            ],
        ];
    }
}
