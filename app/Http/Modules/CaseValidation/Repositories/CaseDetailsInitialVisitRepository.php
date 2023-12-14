<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Modules\CaseValidation\Models\CaseDetailsInitialVisit;
use App\Http\Modules\CaseValidation\Requests\CaseListRequest;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\CaseDetailsInitialVisitRepoInterface;

class CaseDetailsInitialVisitRepository implements CaseDetailsInitialVisitRepoInterface
{
    /**
     * Find CaseValidation and get results in pagination
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     * @param int $rowsPerPage Number of rows to display in a page
     */
    public function find(int $rowsPerPage, CaseListRequest $dto)
    {
        $pending = $dto->pending;
        $completed = $dto->completed;

        try {
            // DB::enableQueryLog(); // Enable query log
            $query = CaseDetailsInitialVisit::query()
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
                ->orderBy('case_id', 'desc');

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
            return CaseDetailsInitialVisit::getTableFields();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
