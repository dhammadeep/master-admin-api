<?php

namespace App\Http\Masters\Gen\Responses\Table;

use App\Http\Masters\Gen\Models\RejectionReason;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Masters\Gen\Responses\Table\RejectionReasonTableResponse;

class RejectionReasonTableCollection extends ResourceCollection
{

    private $rejectionReasonfilter;

    public function __construct($resource, array $rejectionReasonfilter) {
        parent::__construct( $resource );
        $this->rejectionReasonfilter = $rejectionReasonfilter;
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
            'columns' => RejectionReason::getTableFields(),
            'rows' => $this->collection->map(function ($data) {
                return new RejectionReasonTableResponse($data);
            })->all(),
            'filters' => RejectionReason::getFilterFields($this->rejectionReasonfilter),
            'pagination' => new PaginationResource($this),
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
                [
                    'name'=>'approve',
                    'actionUrl'=>'gen/rejection-reason/approve'
                ],
                [
                    'name'=>'reject',
                    'actionUrl'=>'gen/rejection-reason/reject'
                ]
            ],
        ];
    }
}
