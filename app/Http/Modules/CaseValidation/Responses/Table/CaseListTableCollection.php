<?php

namespace App\Http\Modules\CaseValidation\Responses\Table;

use App\Http\Modules\CaseValidation\Models\CaseDetailsInitialVisit;
use App\Http\Resources\Common\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Modules\CaseValidation\Resources\Table\CaseListTableResource;

class CaseListTableCollection extends ResourceCollection
{
    private $tableFields, $filterFields;

    public function __construct($resource, array $tableFields, $filterFields) {
        parent::__construct( $resource );
        $this->tableFields = $tableFields;
        $this->filterFields = $filterFields;
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
            'columns' => $this->tableFields,
            'rows' => $this->collection->map(function ($data) {
                return new CaseListTableResource($data);
            })->all(),
            'pagination' => new PaginationResource($this),
            'filters' => $this->filterFields,
            'actions' => [
                [
                    'name'=>'edit',
                    'actionUrl'=>''
                ],
            ],
        ];
    }
}
