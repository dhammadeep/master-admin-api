<?php

namespace App\Http\Masters\Gen\Responses\Table;

use App\Http\Masters\Gen\Models\BankBranch;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Gen\Responses\Table\BankBranchTableResponse;

class BankBranchTableCollection extends ResourceCollection
{


    private $bankfilter;

    public function __construct($resource, array $bankfilter) {
        parent::__construct( $resource );
        $this->bankfilter = $bankfilter;
    }


    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'columns' => BankBranch::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new BankBranchTableResponse($data);
            })->all(),
            'filters' => BankBranch::getFilterFields($this->bankfilter),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'validation-done',
                    'actionUrl'=>'gen/bank-branch/update-status/validation_done'
                ],
                [
                    'name'=>'validation-fail',
                    'actionUrl'=>'gen/bank-branch/update-status/validation_failed'
                ],
                [
                    'name'=>'verification-done',
                    'actionUrl'=>'gen/bank-branch/update-status/verification_done'
                ],
                [
                    'name'=>'verification-fail',
                    'actionUrl'=>'gen/bank-branch/update-status/verification_failed'
                ]
            ],
        ];
    }
}
