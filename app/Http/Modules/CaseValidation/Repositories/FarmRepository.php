<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use App\Http\Modules\CaseValidation\Models\Farm;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Requests\FarmFormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Enums\CaseValidationStatusEnum;
use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use App\Http\Modules\CaseValidation\Requests\ApproveDocumentRequest;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\FarmRepoInterface;

class FarmRepository implements FarmRepoInterface
{

    /**
     * get cached farm details by farm id
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function findByIdCached(int $farmId)
    {
        //Redis::flushDB();
        try {
            $cacheKey = 'farm:' . $farmId;

            if ($farmDetails = Redis::get($cacheKey)) {
                return $farmDetails = json_decode($farmDetails);
            }

            return $this->findById($farmId);
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
     * @param int $farmId
     * @return \Illuminate\Support\Collection
     */
    public function findById(int $farmId)
    {
        try {
            $farmDetails = Farm::select('id')->find($farmId);

            $cacheKey = 'farm:' . $farmId;
            Redis::setex($cacheKey, 60 * 60 * 2, json_encode($farmDetails));

            return $farmDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * update farm details in the database and return the ID
     *
     * @param FarmKycFormRequest $farmFormRequest
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function updateFarmDetails(FarmFormRequest $farmFormRequest, int $farmId)
    {
        //insert ot update location
        try {

            $farm = Farm::find($farmId);
            $farm->name = $farmFormRequest->farmName;
            $farm->owner_name = $farmFormRequest->ownerName;
            $farm->ownership_type = $farmFormRequest->ownershipType;
            $farm->lessor = $farmFormRequest->lessor;
            $farm->lessees = $farmFormRequest->lessees;
            $farm->registration_no = $farmFormRequest->registrationNo;
            $farm->mutation_no = $farmFormRequest->mutationNo;
            $farm->sale_deed_no = $farmFormRequest->saleDeedNo;
            $farm->registration_date = $farmFormRequest->registrationDate;
            $farm->documented_area_sqm = $farmFormRequest->documentedAreaSqm;
            $farm->leased_from_date = $farmFormRequest->leasedFromDate;
            $farm->leased_till_date = $farmFormRequest->leasedTillDate;
            $farm->survey_no = $farmFormRequest->surveyNo;

            $farm->save();

            return true;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * reject document.
     * @param RejectDocumentRequest $rejectDocumentRequest
     * @param int $farmId
     * @return \Illuminate\Support\Collection
     */
    public function rejectDocument(RejectDocumentRequest $rejectDocumentRequest, int $farmId)
    {
        try {

            $status = CaseValidationStatusEnum::VALIDATION_FAILED->value;

            $farm = Farm::find($farmId);
            $farm->status = $status;
            $farm->save();

            $updateData = array(
                'status' => $status,
                'rejection_reason_id' => $rejectDocumentRequest->rejectionReasonId
            );

            $farm->farmDoc()->updateExistingPivot($rejectDocumentRequest->docId, $updateData);

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
     * approve document.
     * @param ApproveDocumentRequest $approveDocumentRequest
     * @return \Illuminate\Support\Collection
     */
    public function approveDocument(ApproveDocumentRequest $approveDocumentRequest, int $farmId)
    {
        try {

            $status = CaseValidationStatusEnum::VALIDATION_DONE->value;

            $farm = Farm::find($farmId);
            /*$farm = Farm::find($farmId);
            $farm->status = $status;
            $farm->save();*/

            $updateData = array(
                'status' => $status,
                'rejection_reason_id' => null
            );

            $farm->farmDoc()->updateExistingPivot($approveDocumentRequest->docId, $updateData);

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
