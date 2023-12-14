<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use App\Http\Modules\CaseValidation\Models\GeoTag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Enums\CaseValidationStatusEnum;
use App\Http\Modules\CaseValidation\Requests\GeoTagApprovalRequest;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\GeoTagRepoInterface;

class GeoTagRepository implements GeoTagRepoInterface
{

    /**
     * get cached geo tag details by geo Tag id
     *
     * @param int $geoTagId
     * @return \Illuminate\Support\Collection
     */
    public function findByIdCached(int $geoTagId)
    {
        //Redis::flushDB();
        try {
            $cacheKey = 'geo-tag:' . $geoTagId;

            if ($geoTagDetails = Redis::get($cacheKey)) {
                return $geoTagDetails = json_decode($geoTagDetails);
            }

            return $this->findById($geoTagId);

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * get geo Tag details by geo Tag id
     *
     * @param int $geoTagId
     * @return \Illuminate\Support\Collection
     */
    public function findById(int $geoTagId)
    {
        try {
            $geoTagDetails = GeoTag::select('id')->find($geoTagId);

            $cacheKey = 'geo-tag:' . $geoTagId;
            Redis::setex($cacheKey, 60*60*2, json_encode($geoTagDetails));

            return $geoTagDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Geo Tag reject.
     * @param GeoTagApprovalRequest $GeoTagApprovalRequest
     * @return \Illuminate\Support\Collection
     */
    public function geoTagReject(GeoTagApprovalRequest $geoTagApprovalRequest,$geoTagId)
    {
        try {

            $status = CaseValidationStatusEnum::VALIDATION_FAILED->value;

            $geoTagDetails = GeoTag::find($geoTagId);
            $geoTagDetails->status = $status;
            $geoTagDetails->rejection_reason_id = $geoTagApprovalRequest->rejectionReasonId;
            $geoTagDetails->save();

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
     * Geo Tag approve.
     * @param int $geoTagId
     * @return \Illuminate\Support\Collection
     */
    public function geoTagApprove($geoTagId)
    {
        try {

            $status = CaseValidationStatusEnum::VALIDATION_DONE->value;

            $geoTagDetails = GeoTag::find($geoTagId);
            $geoTagDetails->status = $status;
            $geoTagDetails->rejection_reason_id = null;
            $geoTagDetails->save();

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
