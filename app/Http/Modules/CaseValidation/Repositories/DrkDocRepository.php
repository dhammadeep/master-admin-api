<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use App\Http\Modules\CaseValidation\Models\DrkDoc;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Requests\DocValidationRequest;
use App\Http\Modules\CaseValidation\Enums\CaseValidationStatusEnum;
use App\Http\Modules\CaseValidation\Models\CaseDetailsInitialVisit;
use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use App\Http\Modules\CaseValidation\Requests\ApproveDocumentRequest;
use App\Http\Modules\CaseValidation\Requests\DocumentApprovalRequest;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\DrkDocRepoInterface;

class DrkDocRepository implements DrkDocRepoInterface
{
    /**
     * Find CaseValidation and get results in pagination
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     * @param int $rowsPerPage Number of rows to display in a page
     */
   /* public function documentApproval(DocValidationRequest $data, int $id)
    {
        //Get order by id
        try {
            $drkDoc = DrkDoc::findOrFail($id);
        } catch (Exception $e) {
            throw $e;
        }
        //update status and reason
        if ($drkDoc) {
            try {
                return DrkDoc::find($id)->update($data->toArray());
            } catch (QueryException $e) {
                throw $e;
            } catch (Exception $e) {
                throw $e;
            }
        } else {
            throw new Exception;
        }
    }*/

    /**
     * Get the list of table columns for the data table
     *
     * @return array
     */
   /* public function getTableFields(): array
    {
        try {
            return CaseDetailsInitialVisit::getTableFields();
        } catch (Exception $e) {
            throw $e;
        }
    }*/

    /**
     * get cached doc details by doc id
     *
     * @param int $docId
     * @return \Illuminate\Support\Collection
     */
    public function findByIdCached(int $docId)
    {
        //Redis::flushDB();
        try {
            $cacheKey = 'doc:' . $docId;

            if ($docDetails = Redis::get($cacheKey)) {
                return $docDetails = json_decode($docDetails);
            }

            return $this->findById($docId);

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * get doc details by doc id
     *
     * @param int $docId
     * @return \Illuminate\Support\Collection
     */
    public function findById(int $docId)
    {
        try {
            $docDetails = DrkDoc::select('id')->find($docId);

            $cacheKey = 'geo-plot:' . $docDetails;
            Redis::setex($cacheKey, 60*60*2, json_encode($docDetails));

            return $docDetails;

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
    public function rejectDocument(RejectDocumentRequest $rejectDocumentRequest)
    {
        try {

            $status = CaseValidationStatusEnum::VALIDATION_FAILED->value;

            $drkDoc = DrkDoc::findOrFail($rejectDocumentRequest->docId);
            $drkDoc->status = $status;
            $drkDoc->rejection_reason_id = $rejectDocumentRequest->rejectionReasonId;
            $drkDoc->save();

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
    public function approveDocument(ApproveDocumentRequest $approveDocumentRequest)
    {
        try {

            $status = CaseValidationStatusEnum::VALIDATION_DONE->value;

            $drkDoc = DrkDoc::findOrFail($approveDocumentRequest->docId);
            $drkDoc->status = $status;
            $drkDoc->rejection_reason_id = null;
            $drkDoc->save();

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
