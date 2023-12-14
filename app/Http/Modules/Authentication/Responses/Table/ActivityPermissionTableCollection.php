<?php

namespace App\Http\Modules\Authentication\Responses\Table;

use App\Http\Modules\Authentication\Models\ActivityPermission;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Modules\Authentication\Responses\Table\ActivityPermissionTableResponse;

class ActivityPermissionTableCollection extends ResourceCollection
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
            'columns' => ActivityPermission::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new ActivityPermissionTableResponse($data);
            })->all(),
            'filters' => ActivityPermission::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'geo/ActivityPermission/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'geo/ActivityPermission/reject'
                ]
            ],
        ];
    }
}
