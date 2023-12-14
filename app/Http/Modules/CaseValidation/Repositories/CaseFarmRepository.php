<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use App\Http\Modules\CaseValidation\Models\CaseFarm;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\CaseFarmRepoInterface;

class CaseFarmRepository implements CaseFarmRepoInterface
{

    /**
     * get cached farm id by case id
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function findByCaseIdCached(int $caseId)
    {
        Redis::flushDB();
        try {
            $cacheKey = 'case-farm:' . $caseId;

            if ($caseFarmDetails = Redis::get($cacheKey)) {
                return $caseFarmDetails = json_decode($caseFarmDetails);
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
     * get farm details by farm id
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function findByCaseId(int $caseId)
    {
        try {
            $caseFarmDetails = CaseFarm::select('farm_id')->where('case_id',$caseId)->first();

            $cacheKey = 'case-farm:' . $caseId;

            Redis::setex($cacheKey, 60*60*2, json_encode($caseFarmDetails));

            return $caseFarmDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
