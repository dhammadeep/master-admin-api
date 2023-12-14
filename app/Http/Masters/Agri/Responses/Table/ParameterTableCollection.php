<?php

namespace App\Http\Masters\Agri\Responses\Table;

use App\Http\Masters\Agri\Models\Parameter;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Agri\Responses\Table\ParameterTableResponse;

class ParameterTableCollection extends ResourceCollection
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
            'columns' => Parameter::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new ParameterTableResponse($data);
            })->all(),
            'filters' => Parameter::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'agri/parameter/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'agri/parameter/reject'
                ]
            ],
        ];
    }
}
