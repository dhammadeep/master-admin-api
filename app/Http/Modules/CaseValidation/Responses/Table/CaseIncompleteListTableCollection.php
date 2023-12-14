<?php

namespace App\Http\Modules\CaseValidation\Responses\Table;

use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Modules\IncompleteCase\Models\CaseListIncomplete;
use App\Http\Modules\CaseValidation\Resources\Table\CaseIncompleteListTableResource;

class CaseIncompleteListTableCollection extends ResourceCollection
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
            'columns' => CaseListIncomplete::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new CaseIncompleteListTableResource($data);
            })->all(),
            'pagination' => new PaginationResource($this),
            'filters' => CaseListIncomplete::getFilterFields(),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
            ],
        ];
    }
}
