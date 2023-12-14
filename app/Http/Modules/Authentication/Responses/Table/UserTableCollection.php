<?php

namespace App\Http\Modules\Authentication\Responses\Table;

use App\Http\Modules\Authentication\Models\User;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Modules\Authentication\Responses\Table\UserTableResponse;

class UserTableCollection extends ResourceCollection
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
            'columns' => User::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new UserTableResponse($data);
            })->all(),
            'filters' => User::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>''
                ]
            ],
        ];
    }
}
