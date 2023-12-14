<?php

namespace App\Http\Modules\Authentication\Responses\Table;

use App\Http\Modules\Authentication\Models\Activity;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Modules\Authentication\Responses\Table\RoleTableResponse;

class RoleTableCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'columns' => Activity::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new RoleTableResponse($data);
            })->all(),
            'filters' => Activity::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                // [
                //     'name'=>'approve',
                //     'actionUrl'=>'user/role/approve'
                // ],
                // [
                //     'name'=>'reject',
                //     'actionUrl'=>'user/role/reject'
                // ]
            ],
        ];
    }
}
