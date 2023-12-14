<?php

namespace App\Http\Modules\IncompleteCase\Repositories;

use Exception;
use Illuminate\Http\Request;
use App\Http\Modules\IncompleteCase\Models\CaseListIncompleteUnpaid;
use App\Http\Modules\IncompleteCase\Repositories\RepoInterface\CaseListIncompleteUnpaidRepoInterface;


class CaseListIncompleteUnpaidRepository implements CaseListIncompleteUnpaidRepoInterface
{
    /**
     * Find CaseValidation and get results in pagination
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     * @param int $rowsPerPage Number of rows to display in a page
     */
    public function getList(Request $request, int $rowsPerPage)
    {
        $rowsPerPage = $request->size;
        $order = 'desc';
        if(!empty($request->order)){
            $order = $request->order;
        }
        $orderBy = 'case_id';
        if(!empty($request->orderBy)){
            $orderBy = $request->orderBy;
        }
        $request = $request->except(['page','size','order','orderBy']);

        try {
            // DB::enableQueryLog(); // Enable query log
            $query = CaseListIncompleteUnpaid::query()
                ->select(
                    'case_id',
                    'tender_type',
                    'user_full_name',
                    'user_mobile',
                    'language_name'
                )
                ->orderBy($orderBy, $order);

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
            return CaseListIncompleteUnpaid::getTableFields();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
