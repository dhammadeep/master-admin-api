<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use App\Http\Modules\CaseValidation\Models\CaseVisit;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Requests\CaseVisitReschedulingRequest;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\CaseVisitRepoInterface;

class CaseVisitRepository implements CaseVisitRepoInterface
{

    /**
     * get cached farm details by case id
     *
     * @param int $caseId
     * @return \Illuminate\Support\Collection
     */
    public function findByCaseIdCached(int $caseId)
    {
        //Redis::flushDB();
        try {
            $cacheKey = 'case-visit:' . $caseId;

            if ($caseVisitDetails = Redis::get($cacheKey)) {
                return $caseVisitDetails = json_decode($caseVisitDetails);
            }

            return $this->findByCaseId($caseId);

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * get geo plot details by case id
     *
     * @param int $caseId
     * @return \Illuminate\Support\Collection
     */
    public function findByCaseId(int $caseId)
    {
        try {
            $caseVisitDetails = CaseVisit::select('id')->where('case_id',$caseId)->first();

            $cacheKey = 'case-visit:' . $caseId;
            Redis::setex($cacheKey, 60*60*2, json_encode($caseVisitDetails));

            return $caseVisitDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
