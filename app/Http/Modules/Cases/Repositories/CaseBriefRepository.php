<?php

namespace App\Http\Modules\Cases\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Http\Modules\Cases\Models\CaseBrief;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\Cases\Repositories\RepoInterface\CaseBriefRepoInterface;

class CaseBriefRepository implements CaseBriefRepoInterface
{
    /**
     * Get the widget structure and document details from the database.
     *
     * @param int $caseId
     * @return \Illuminate\Support\Collection
     */
    public function getBriefDetails(int $caseId)
    {
        try {
            //$caseDetail = CaseBrief::where('case_id', $caseId)->first();
            return collect(DB::select(
                "call GetCaseBrief($caseId)"
            ))->first();
            //dd($caseDetail, $caseDetails);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
