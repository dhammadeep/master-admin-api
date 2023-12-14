<?php

namespace App\Http\Modules\IncompleteCase\Services;

use Exception;
use Illuminate\Http\Request;
use App\Http\Modules\IncompleteCase\Repositories\CaseListIncompleteRepository;
use App\Http\Modules\IncompleteCase\Repositories\CaseListIncompleteUnpaidRepository;
use App\Http\Modules\IncompleteCase\Repositories\CaseListIncompletePendingRepository;
use App\Http\Modules\CaseValidation\Responses\Table\CaseIncompleteListTableCollection;

class IncompleteCaseService
{
    protected $repository, $caseListIncompletePending, $caseListIncompleteUnpaid;

    /**
     * Constructor based dependency injection
     *
     * @param CaseDetailsInitialVisitRepository $repository
     *
     * @return void
     */
    public function __construct(CaseListIncompleteRepository $repository, CaseListIncompletePendingRepository $caseListIncompletePending, CaseListIncompleteUnpaidRepository $caseListIncompleteUnpaid)
    {
        $this->repository = $repository;
        $this->caseListIncompletePending = $caseListIncompletePending;
        $this->caseListIncompleteUnpaid = $caseListIncompleteUnpaid;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return CaseListTableCollection
     */
    public function getAllPaginatedTableData()
    {
        // Get number of rows to display in a DataTable
        // from the global configuration
        $rowsPerPage = config('custom.dataTablePagination');

        // Retrieve the paginated table data
        try {
            return new CaseIncompleteListTableCollection($this->repository->getList($rowsPerPage));
        } catch (Exception $e) {
            throw $e;
        }

        // Generate filter options based on the retrieved data
        // $filters = [
        //     [
        //         'name' => 'crop_type_id',
        //         'label' => 'Crop Type',
        //         'value' => [],
        //         'type' => 'multiselect',
        //         'validators' => ['required' => false],
        //         'options' => $tableData->pluck('crop_type_name', 'crop_type_id')->map(function ($label, $value) {
        //             return ['label' => $label, 'value' => $value];
        //         })->values(),
        //     ],
        //     [
        //         'name' => 'regions',
        //         'label' => 'Region',
        //         'value' => [],
        //         'type' => 'multiselect',
        //         'validators' => ['required' => false],
        //         'options' => $tableData->pluck('region_name', 'region_id')->map(function ($label, $value) {
        //             return ['label' => $label, 'value' => $value];
        //         })->values(),
        //     ],
        //     [
        //         'name' => 'languages',
        //         'label' => 'Language',
        //         'value' => [],
        //         'type' => 'multiselect',
        //         'validators' => ['required' => false],
        //         'options' => $tableData->pluck('language_name', 'language_id')->map(function ($label, $value) {
        //             return ['label' => $label, 'value' => $value];
        //         })->values(),
        //     ],
        // ];

        // Return the response array including both data and filters
        // return [
        //     // 'filters' => $filters,
        //     'data' => new CaseListTableCollection($tableData),
        // ];
    }

     /**
     * Get pending case list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return CaseListTableCollection
     */
    public function getPendingList(Request $request)
    {
        // Get number of rows to display in a DataTable
        // from the global configuration
        $rowsPerPage = config('custom.dataTablePagination');

        // Retrieve the paginated table data
        try {
            return new CaseIncompleteListTableCollection($this->caseListIncompletePending->getList($request, $rowsPerPage));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get unpaid case list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return CaseListTableCollection
     */
    public function getUnpaidList(Request $request)
    {
        // Get number of rows to display in a DataTable
        // from the global configuration
        $rowsPerPage = config('custom.dataTablePagination');

        // Retrieve the paginated table data
        try {
            return new CaseIncompleteListTableCollection($this->caseListIncompleteUnpaid->getList($request, $rowsPerPage));
        } catch (Exception $e) {
            throw $e;
        }
    }
}
