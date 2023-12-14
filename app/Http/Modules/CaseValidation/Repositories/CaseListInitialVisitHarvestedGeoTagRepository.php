<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Modules\CaseValidation\Requests\CaseListRequest;
use App\Http\Modules\CaseValidation\Models\CaseListInitialVisitHarvestedGeoTag;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\CaseListInitialVisitHarvestedGeoTagRepoInterface;

class CaseListInitialVisitHarvestedGeoTagRepository implements CaseListInitialVisitHarvestedGeoTagRepoInterface
{
    /**
     * Find CaseValidation and get results in pagination
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     * @param int $rowsPerPage Number of rows to display in a page
     */
    public function getList(Request $request, int $rowsPerPage, CaseListRequest $dto)
    {
        $rowsPerPage = $request->size;
        $order = 'desc';
        if (!empty($request->order)) {
            $order = $request->order;
        }
        $orderBy = 'case_id';
        if (!empty($request->orderBy)) {
            $orderBy = $request->orderBy;
        }
        $request = $request->except(['page', 'size', 'order', 'orderBy']);

        $pending = $dto->pending;
        $completed = $dto->completed;

        try {
            // DB::enableQueryLog(); // Enable query log
            $query = CaseListInitialVisitHarvestedGeoTag::query()
                ->select(
                    'case_id',
                    'crop_type',
                    'user_full_name',
                    'user_mobile',
                    'language_name',
                    'region_name',
                    'payment_status',
                    'case_status',
                    'user_status',
                    'farm_status',
                    'company_status',
                    'case_warehouse_status',
                    'bank_account_status'
                )
                ->when(Arr::hasAny($request,['user_full_name','region_name']), function ($query) use($request){
                    if(isset($request['user_full_name'])){
                        $query->where('user_full_name', 'like', "%{$request['user_full_name']}%");
                    }
                    if(isset($request['region_name'])){
                        $query->where('region_name', 'like', "%{$request['region_name']}%");
                    }
                }, function($query) use($request){
                    $query->where($request);
                })
                ->orderBy($orderBy, $order);

            // Filter by user_status as the primary condition
            $query->where('case_status', 'DOCUMENTS_SUBMITTED');

            // Filter by pending columns
            if (!empty($pending)) {
                if (is_array($pending)) {
                    $query->where(function ($query) use ($pending) {
                        foreach ($pending as $column) {
                            $query->orWhere($column, '=', 'DOCUMENT_SUBMITTED');
                        }
                    });
                } else {
                    $query->where($pending, '=', 'DOCUMENT_SUBMITTED');
                }
            }

            // Filter by completed columns
            if (!empty($completed)) {
                if (is_array($completed)) {
                    $completed = $completed;
                    $query->where(function ($query) use ($completed) {
                        foreach ($completed as $column) {
                            $query->orWhere($column, '=', 'VALIDATION_DONE');
                        }
                    });
                } else {
                    $query->where($completed, '=', 'VALIDATION_DONE');
                }
            }

            $result = $query->paginate($rowsPerPage)
                ->appends(request()->query());

            //   dd(DB::getQueryLog());
            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the list of table columns for the data table
     *
     * @return array
     */
    public function getTableFields(): array
    {
        try {
            return CaseListInitialVisitHarvestedGeoTag::getTableFields();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * get Dropdowns list for cropType
     */
    public function listCropType(CaseListRequest $caseListOptions)
    {
        $pending = $caseListOptions->pending;
        $completed = $caseListOptions->completed;

        try {
            $query =  CaseListInitialVisitHarvestedGeoTag::select('crop_type_id', 'crop_type')
            ->orderBy('crop_type_id', 'asc')
            ->groupBy('crop_type_id');
             // Filter by pending columns
             if (!empty($pending)) {
                if (is_array($pending)) {
                    $query->where(function ($query) use ($pending) {
                        foreach ($pending as $column) {
                            $query->orWhere($column, '=', 'DOCUMENT_SUBMITTED');
                        }
                    });
                } else {
                    $query->where($pending, '=', 'DOCUMENT_SUBMITTED');
                }
            }

            // Filter by completed columns
            if (!empty($completed)) {
                if (is_array($completed)) {
                    $completed = $completed;
                    $query->where(function ($query) use ($completed) {
                        foreach ($completed as $column) {
                            $query->orWhere($column, '=', 'VALIDATION_DONE');
                        }
                    });
                } else {
                    $query->where($completed, '=', 'VALIDATION_DONE');
                }
            }
            $result = $query->get();
            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
