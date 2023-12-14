<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use App\Http\Modules\CaseValidation\Models\GeoPlot;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Enums\CaseValidationStatusEnum;
use App\Http\Modules\CaseValidation\Requests\GeoPlottingApprovalRequest;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\GeoPlotRepoInterface;

class GeoPlotRepository implements GeoPlotRepoInterface
{

    /**
     * get cached geo plot details by geo plot id
     *
     * @param int $geoPlotId
     * @return \Illuminate\Support\Collection
     */
    public function findByIdCached(int $geoPlotId)
    {
        //Redis::flushDB();
        try {
            $cacheKey = 'geo-plot:' . $geoPlotId;

            if ($geoPlotDetails = Redis::get($cacheKey)) {
                return $geoPlotDetails = json_decode($geoPlotDetails);
            }

            return $this->findById($geoPlotId);

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * get geo plot details by geo plot id
     *
     * @param int $geoPlotId
     * @return \Illuminate\Support\Collection
     */
    public function findById(int $geoPlotId)
    {
        try {
            $geoPlotDetails = GeoPlot::select('id')->find($geoPlotId);

            $cacheKey = 'geo-plot:' . $geoPlotId;
            Redis::setex($cacheKey, 60*60*2, json_encode($geoPlotDetails));

            return $geoPlotDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Geo Plotting rejection.
     * @param GeoPlottingApprovalRequest $GeoPlottingApprovalRequest
     * @return \Illuminate\Support\Collection
     */
    public function geoPlotReject(GeoPlottingApprovalRequest $geoPlottingApprovalRequest,$geoPlotId)
    {
        try {

            $status = CaseValidationStatusEnum::VALIDATION_FAILED->value;

            $geoPlotDetails = GeoPlot::find($geoPlotId);
            $geoPlotDetails->status = $status;
            $geoPlotDetails->rejection_reason_id = $geoPlottingApprovalRequest->rejectionReasonId;
            $geoPlotDetails->save();

            return Null;

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Geo Plotting approve.
     * @param GeoPlottingApprovalRequest $GeoPlottingApprovalRequest
     * @return \Illuminate\Support\Collection
     */
    public function geoPlotApprove($geoPlotId)
    {
        try {

            $status = CaseValidationStatusEnum::VALIDATION_DONE->value;

            $geoPlotDetails = GeoPlot::find($geoPlotId);
            $geoPlotDetails->status = $status;
            $geoPlotDetails->rejection_reason_id = null;
            $geoPlotDetails->save();

            return Null;

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
