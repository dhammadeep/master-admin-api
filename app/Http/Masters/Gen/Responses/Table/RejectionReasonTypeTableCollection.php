<?php

namespace App\Http\Masters\Gen\Responses\Table;

use App\Http\Masters\Gen\Models\RejectionReasonType;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Gen\Responses\Table\RejectionReasonTypeTableResponse;

class RejectionReasonTypeTableCollection extends ResourceCollection
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
            'columns' => RejectionReasonType::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new RejectionReasonTypeTableResponse($data);
            })->all(),
            'filters' => RejectionReasonType::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'gen/rejection-reason-type/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'gen/rejection-reason-type/reject'
                ]
            ],
        ];
    }
}
