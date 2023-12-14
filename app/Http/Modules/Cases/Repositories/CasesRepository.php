<?php

namespace App\Http\Modules\Cases\Repositories;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use App\Http\Modules\Cases\Models\Cases;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Enums\CaseValidationStatusEnum;
use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use App\Http\Modules\Cases\Repositories\RepoInterface\CasesRepoInterface;

class CasesRepository implements CasesRepoInterface
{

    /**
     * get cached case details by case id
     *
     * @param int $caseId
     * @return \Illuminate\Support\Collection
     */
    public function findByIdCached(int $caseId)
    {
        //Redis::flushDB();
        try {
            $cacheKey = 'case:' . $caseId;

            if ($caseDetails = Redis::get($cacheKey)) {
                return json_decode($caseDetails);
            }

            return $this->findById($caseId);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * get case details by case id
     *
     * @param int $caseId
     * @return \Illuminate\Support\Collection
     */
    public function findById(int $caseId)
    {
        try {
            $status = CaseValidationStatusEnum::VALIDATION_FAILED->value;

            $caseDetails = Cases::select('id', 'user_id', 'crop_type_id', 'bank_account_id', 'is_trader')->where('status','!=', $status)->find($caseId);

            $cacheKey = 'case:' . $caseId;
            Redis::setex($cacheKey, 60 * 60 * 2, json_encode($caseDetails));

            return $caseDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * reject case status by case id
     *
     * @param int $caseId
     * @return \Illuminate\Support\Collection
     */
    public function rejectCase(int $rejectionReasonId, int $caseId)
    {
        $status = CaseValidationStatusEnum::VALIDATION_FAILED->value;

        try {
            $caseDetails = Cases::findOrFail($caseId);
            $caseDetails->status = $status;
            $caseDetails->rejection_reason_id = $rejectionReasonId;
            $caseDetails->save();

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
