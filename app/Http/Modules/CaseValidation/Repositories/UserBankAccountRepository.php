<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Models\UserBankAccount;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Enums\CaseValidationStatusEnum;
use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use App\Http\Modules\CaseValidation\Requests\ApproveDocumentRequest;
use App\Http\Modules\CaseValidation\Requests\UserBankAccountFormRequest;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\UserBankAccountRepoInterface;

class UserBankAccountRepository implements UserBankAccountRepoInterface
{

    /**
     * get cached user bank account details, to check whether case bank account id is exist or not
     *
     * @param int $bankAccountId
     * @return \Illuminate\Support\Collection
     */
    public function findByBankAccountIdCached(int $bankAccountId)
    {
        //Redis::flushDB();
        try {
            $cacheKey = 'user-bank-account:' . $bankAccountId;

            if ($UserBankAccountDetails = Redis::get($cacheKey)) {

                return $UserBankAccountDetails = json_decode($UserBankAccountDetails);
            }

            return $this->findByBankAccountId($bankAccountId);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * get bank account details by bank account id
     *
     * @param int $bankAccountId
     * @return \Illuminate\Support\Collection
     */
    public function findByBankAccountId(int $bankAccountId)
    {
        try {
            $userBankAccountDetails = UserBankAccount::select('id')->find($bankAccountId);
            $cacheKey = 'user-bank-account:' . $bankAccountId;
            Redis::setex($cacheKey, 60 * 60 * 2, json_encode($userBankAccountDetails));

            return $userBankAccountDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * update bank account details in the database and return the ID
     *
     * @param UserBankAccountFormRequest $userBankAccountFormRequest
     * @param int $userBankAccountId
     * @return \Illuminate\Support\Collection
     */
    public function updateUserBankAccountDetails(UserBankAccountFormRequest $userBankAccountFormRequest, int $userBankAccountId)
    {
        //insert ot update location
        try {

            $userBankAccount = UserBankAccount::find($userBankAccountId);
            $userBankAccount->bank_branch_id = $userBankAccountFormRequest->bankBranchId;
            $userBankAccount->account_name = $userBankAccountFormRequest->accountName;
            $userBankAccount->account_number = $userBankAccountFormRequest->accountNumber;
            $userBankAccount->save();


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
    public function rejectDocument(RejectDocumentRequest $rejectDocumentRequest, int $userBankAccountId)
    {
        try {

            $status = CaseValidationStatusEnum::VALIDATION_FAILED->value;

            $userBankAccount = UserBankAccount::find($userBankAccountId);
            /*$userBankAccount->status = $status;
            $userBankAccount->save();*/

            $updateData = array(
                'status' => $status,
                'rejection_reason_id' => $rejectDocumentRequest->rejectionReasonId
            );

            $userBankAccount->userBankAccountDoc()->updateExistingPivot($rejectDocumentRequest->docId, $updateData);

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
    public function approveDocument(ApproveDocumentRequest $approveDocumentRequest, int $userBankAccountId)
    {
        try {

            $status = CaseValidationStatusEnum::VALIDATION_DONE->value;

            $userBankAccount = UserBankAccount::find($userBankAccountId);
            $userBankAccount->status = $status;
            $userBankAccount->save();

            $updateData = array(
                'status' => $status,
                'rejection_reason_id' => null
            );

            $userBankAccount->userBankAccountDoc()->updateExistingPivot($approveDocumentRequest->docId, $updateData);

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
