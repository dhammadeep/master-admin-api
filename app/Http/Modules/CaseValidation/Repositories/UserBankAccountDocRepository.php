<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Models\UserBankAccountDoc;
use App\Http\Modules\CaseValidation\Requests\DocumentApprovalRequest;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\UserBankAccountDocRepoInterface;

class UserBankAccountDocRepository implements UserBankAccountDocRepoInterface
{
    /**
     * get cached user bank account details, to check whether case bank account id is exist or not
     *
     * @param int $userBankAccountId
     * @param int $docId
     * @return \Illuminate\Support\Collection
     */
    public function findByUserBankAccountIdAndDocIdCached($userBankAccountId,$docId)
    {
        //Redis::flushDB();
        try {
            $cacheKey = 'user-bank-account-doc:' .$docId;

            if ($userBankAccountDocDetails = Redis::get($cacheKey)) {

                return $userBankAccountDocDetails = json_decode($userBankAccountDocDetails);
            }

            return $this->findByUserBankAccountIdAndDocId($userBankAccountId,$docId);

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
     * @param int $userBankAccountId
     * @param int $docId
     * @return \Illuminate\Support\Collection
     */
    public function findByUserBankAccountIdAndDocId($userBankAccountId,$docId)
    {
        try {
            $userBankAccountDocDetails = UserBankAccountDoc::select('bank_account_id','doc_id')->where('bank_account_id',$userBankAccountId)->where('doc_id',$docId)->first();

            $cacheKey = 'user-bank-account-doc:' .$docId;
            Redis::setex($cacheKey, 60*60*2, json_encode($userBankAccountDocDetails));

            return $userBankAccountDocDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
