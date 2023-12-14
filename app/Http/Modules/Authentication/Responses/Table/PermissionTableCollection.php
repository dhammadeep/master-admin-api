<?php

namespace App\Http\Modules\Authentication\Responses\Table;

use App\Http\Modules\Authentication\Models\Permission;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Modules\Authentication\Responses\Table\PermissionTableResponse;

class PermissionTableCollection extends ResourceCollection
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
            'columns' => Permission::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new PermissionTableResponse($data);
            })->all(),
            'filters' => Permission::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                // [
                //     'name'=>'approve',
                //     'actionUrl'=>'permission/approve'
                // ],
                // [
                //     'name'=>'reject',
                //     'actionUrl'=>'permission/reject'
                // ]
            ],
        ];
    }
}
