<?php

namespace App\Http\Masters\Agri\Responses\Table;

use App\Http\Masters\Agri\Models\Phenophase;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Agri\Responses\Table\PhenophaseTableResponse;

class PhenophaseTableCollection extends ResourceCollection
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
            'columns' => Phenophase::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new PhenophaseTableResponse($data);
            })->all(),
            'filters' => Phenophase::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'agri/phenophase/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'agri/phenophase/reject'
                ]
            ],
        ];
    }
}
