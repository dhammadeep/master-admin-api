<?php

namespace App\Http\Masters\Gen\Responses\Table;

use App\Http\Masters\Gen\Models\Bank;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Gen\Responses\Table\BankTableResponse;

class BankTableCollection extends ResourceCollection
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
            'columns' => Bank::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new BankTableResponse($data);
            })->all(),
            'filters' => Bank::getFilterFields(),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'validation-done',
                    'actionUrl'=>'gen/bank/update-status/validation_done'
                ],
                [
                    'name'=>'validation-fail',
                    'actionUrl'=>'gen/bank/update-status/validation_failed'
                ],
                [
                    'name'=>'verification-done',
                    'actionUrl'=>'gen/bank/update-status/verification_done'
                ],
                [
                    'name'=>'verification-fail',
                    'actionUrl'=>'gen/bank/update-status/verification_failed'
                ]
            ],
        ];
    }
}
