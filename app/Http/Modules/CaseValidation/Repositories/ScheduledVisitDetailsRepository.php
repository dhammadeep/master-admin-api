<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use App\Http\Modules\CaseValidation\Models\ScheduledVisitDetails;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\ScheduledVisitDetailsRepoInterface;

class ScheduledVisitDetailsRepository implements ScheduledVisitDetailsRepoInterface
{

    /**
     * Get the scheduled visit details from the database.
     *
     * @param int $caseId
     * @return \Illuminate\Support\Collection
     */
    public function getScheduledVisitDetails(int $caseId)
    {
        try {
            $caseWarehouseDetails = ScheduledVisitDetails::select('scheduled_dates')->where('case_id', $caseId)->first();

            if(is_null($caseWarehouseDetails)){
                throw new Exception;
            }
            return $caseWarehouseDetails;

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

}
