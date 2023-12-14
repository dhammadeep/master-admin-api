<?php

namespace App\Http\Masters\Agri\Responses\Table;

use App\Http\Masters\Agri\Models\CropType;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Agri\Responses\Table\CropTypeTableResponse;

class CropTypeTableCollection extends ResourceCollection
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
            'columns' => CropType::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new CropTypeTableResponse($data);
            })->all(),
            'filters' => CropType::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'agri/crop-type/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'agri/crop-type/reject'
                ]
            ],
        ];
    }
}
