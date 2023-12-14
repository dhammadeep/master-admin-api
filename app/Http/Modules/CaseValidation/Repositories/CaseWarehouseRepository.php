<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Models\CaseWarehouse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Requests\WarehouseFormRequest;
use App\Http\Modules\CaseValidation\Enums\CaseValidationStatusEnum;
use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use App\Http\Modules\CaseValidation\Requests\ApproveDocumentRequest;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\CaseWarehouseRepoInterface;

class CaseWarehouseRepository implements CaseWarehouseRepoInterface
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
            $cacheKey = 'case-warehouse:' . $caseId;

            if ($caseWarehouseDetails = Redis::get($cacheKey)) {
                return $caseWarehouseDetails = json_decode($caseWarehouseDetails);
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
            $caseWarehouseDetails = CaseWarehouse::select('id', 'warehouse_id')->where('case_id', $caseId)->first();

            $cacheKey = 'case-warehouse:' . $caseId;

            Redis::setex($cacheKey, 60 * 60 * 2, json_encode($caseWarehouseDetails));

            return $caseWarehouseDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * update Case Warehouse details in the database and return the ID
     *
     * @param WarehouseKycFormRequest $warehouseFormRequest
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function updateCaseWarehouseDetails(WarehouseFormRequest $warehouseFormRequest, int $warehouseId)
    {

        //update case warehouse details
        try {

            $caseWarehouse = CaseWarehouse::where('warehouse_id', $warehouseId)->first();
            $caseWarehouse->warehouse_lot_no = $warehouseFormRequest->warehouseLotNo;
            $caseWarehouse->total_bag_count = $warehouseFormRequest->totalBagCount;
            //$caseWarehouse->no_of_stacks = $warehouseFormRequest->noOfStacks;
            $caseWarehouse->save();

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
     * @return \Illuminate\Support\Collection
     */
    public function rejectDocument(RejectDocumentRequest $rejectDocumentRequest, int $caseWarehouseId)
    {
        try {

            $status = CaseValidationStatusEnum::VALIDATION_FAILED->value;

            $caseWarehouse = CaseWarehouse::find($caseWarehouseId);
            $caseWarehouse->status = $status;
            $caseWarehouse->save();

            $updateData = array(
                'status' => $status,
                'rejection_reason_id' => $rejectDocumentRequest->rejectionReasonId
            );

            $caseWarehouse->warehouseDoc()->updateExistingPivot($rejectDocumentRequest->docId, $updateData);

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
     * @param RejectDocumentRequest $rejectDocumentRequest
     * @return \Illuminate\Support\Collection
     */
    public function approveDocument(ApproveDocumentRequest $approveDocumentRequest, int $caseWarehouseId)
    {
        try {

            $status = CaseValidationStatusEnum::VALIDATION_DONE->value;

            $caseWarehouse = CaseWarehouse::find($caseWarehouseId);
           /*
            $caseWarehouse->status = $status;
            $caseWarehouse->save();*/

            $updateData = array(
                'status' => $status,
                'rejection_reason_id' => null
            );

            $caseWarehouse->warehouseDoc()->updateExistingPivot($approveDocumentRequest->docId, $updateData);

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
