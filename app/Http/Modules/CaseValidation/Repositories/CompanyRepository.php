<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use App\Http\Modules\CaseValidation\Models\Company;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Requests\CompanyFormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Enums\CaseValidationStatusEnum;
use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use App\Http\Modules\CaseValidation\Requests\ApproveDocumentRequest;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\CompanyRepoInterface;

class CompanyRepository implements CompanyRepoInterface
{

    /**
     * get cached Company details by Company id
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function findByIdCached(int $companyId)
    {
        //Redis::flushDB();
        try {
            $cacheKey = 'company:' . $companyId;

            if ($companyDetails = Redis::get($cacheKey)) {
                $companyDetails = json_decode($companyDetails);

                if($companyDetails->location_id){
                    return $companyDetails;
               }else{
                    return $this->findById($companyId);
               }
            }

            return $this->findById($companyId);

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * get Company details by Company id
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function findById(int $companyId)
    {
        try {
            $companyDetails = Company::select('id','location_id')->find($companyId);

            $cacheKey = 'company:' . $companyId;
            Redis::setex($cacheKey, 60*60*2, json_encode($companyDetails));

            return $companyDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Check company location duplication.
     *
     * @param int $companyId
     * @param int $locationId
     * @return \Illuminate\Support\Collection
     */
    public function checkCompanyLocationExistOrNot(int $companyId,int $locationId)
    {
        try {
            // Perform the check in the database and return the result
            return Company::select('id','location_id')->where('id','!=',$companyId)->where('location_id',$locationId)->exists();

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

     /**
     * update Company details in the database and return the ID
     *
     * @param CompanyKycFormRequest $companyFormRequest
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function updateCompanyDetails(CompanyFormRequest $companyFormRequest,int $companyId)
    {
        //insert ot update location
        try {

            $company = Company::find($companyId);
            $company->name = $companyFormRequest->companyName;
            $company->location_id = $companyFormRequest->locationId;
            $company->business_entity_type = $companyFormRequest->businessEntityType;
            $company->license_no = $companyFormRequest->licenseNo;
            $company->license_issue_date = $companyFormRequest->licenseIssueDate;
            $company->license_expiry_date = $companyFormRequest->licenseExpiryDate;
            $company->date_of_incorporation = $companyFormRequest->dateOfIncorporation;
            $company->pan = $companyFormRequest->pan;
            $company->cin = $companyFormRequest->cin;
            $company->gstn = $companyFormRequest->gstn;
            $company->website = $companyFormRequest->website;
            $company->nationality = $companyFormRequest->nationality;
            $company->constitution = $companyFormRequest->constitution;
            $company->save();

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
    public function rejectDocument(RejectDocumentRequest $rejectDocumentRequest,int $companyId)
    {
        try {

            $status = CaseValidationStatusEnum::VALIDATION_FAILED->value;

            $company = Company::find($companyId);
            $company->status = $status;
            $company->save();

            $updateData = array(
                'status' => $status,
                'rejection_reason_id' => $rejectDocumentRequest->rejectionReasonId
            );

           $company->companyDoc()->updateExistingPivot($rejectDocumentRequest->docId,$updateData);

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
    public function approveDocument(ApproveDocumentRequest $approveDocumentRequest,int $companyId)
    {
        try {

            $status = CaseValidationStatusEnum::VALIDATION_DONE->value;

            $company = Company::find($companyId);
            /*
            $company->status = $status;
            $company->save();*/

            $updateData = array(
                'status' => $status,
                'rejection_reason_id' => null
            );

           $company->companyDoc()->updateExistingPivot($approveDocumentRequest->docId,$updateData);

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
