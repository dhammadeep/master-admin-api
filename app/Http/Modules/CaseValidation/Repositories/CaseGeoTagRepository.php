<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use App\Http\Modules\CaseValidation\Models\CaseGeoTag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\CaseGeoTagRepoInterface;

class CaseGeoTagRepository implements CaseGeoTagRepoInterface
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
            $cacheKey = 'case-geo-tag:' . $caseId;

            if ($caseGeoTagDetails = Redis::get($cacheKey)) {
                return $caseGeoTagDetails = json_decode($caseGeoTagDetails);
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
     * get geo Tag details by case id
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function findByCaseId(int $caseId)
    {
        try {
            $caseGeoTagDetails = CaseGeoTag::select('geo_tag_id')->where('case_id',$caseId)->first();

            $cacheKey = 'case-geo-Tag:' . $caseId;

            Redis::setex($cacheKey, 60*60*2, json_encode($caseGeoTagDetails));

            return $caseGeoTagDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
