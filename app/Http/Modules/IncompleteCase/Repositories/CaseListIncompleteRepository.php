<?php

namespace App\Http\Modules\IncompleteCase\Repositories;

use Exception;
use App\Http\Modules\IncompleteCase\Models\CaseListIncomplete;
use App\Http\Modules\IncompleteCase\Repositories\RepoInterface\CaseListIncompleteRepoInterface;


class CaseListIncompleteRepository implements CaseListIncompleteRepoInterface
{
    /**
     * Find CaseValidation and get results in pagination
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     * @param int $rowsPerPage Number of rows to display in a page
     */
    public function getList(int $rowsPerPage)
    {

        try {
            // DB::enableQueryLog(); // Enable query log
            $query = CaseListIncomplete::query()
                ->select(
                    'case_id',
                    'tender_type',
                    'user_full_name',
                    'user_mobile',
                    'language_name'
                )
                ->orderBy('case_id', 'desc');

            // Filter by user_status as the primary condition
            $query->where('case_status', 'PENDING');


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
            return CaseListIncomplete::getTableFields();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
