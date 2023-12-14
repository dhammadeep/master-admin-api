<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use App\Http\Modules\CaseValidation\Models\CaseGeoPlot;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\CaseGeoPlotRepoInterface;

class CaseGeoPlotRepository implements CaseGeoPlotRepoInterface
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
            $cacheKey = 'case-geo-plot:' . $caseId;

            if ($caseGeoPlotDetails = Redis::get($cacheKey)) {
                return $caseGeoPlotDetails = json_decode($caseGeoPlotDetails);
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
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function findByCaseId(int $caseId)
    {
        try {
            $caseGeoPlotDetails = CaseGeoPlot::select('geo_plot_id')->where('case_id',$caseId)->first();

            $cacheKey = 'case-geo-plot:' . $caseId;

            Redis::setex($cacheKey, 60*60*2, json_encode($caseGeoPlotDetails));

            return $caseGeoPlotDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
