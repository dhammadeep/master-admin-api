<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use App\Http\Modules\CaseValidation\Models\CompanyDoc;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Requests\DocumentApprovalRequest;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\CompanyDocRepoInterface;

class CompanyDocRepository implements CompanyDocRepoInterface
{
    /**
     * get cached user company details, to check whether user company id is exist or not
     *
     * @param int $userBankAccountId
     * @param int $docId
     * @return \Illuminate\Support\Collection
     */
    public function findByCompanyIdAndDocIdCached($companyId, $docId)
    {
        Redis::flushDB();
        try {
            $cacheKey = 'user-company-doc:' . $docId;
            $userCompanyDocDetails = Redis::get($cacheKey);
            if (!empty($userCompanyDocDetails)) {
                return $userCompanyDocDetails = json_decode($userCompanyDocDetails);
            }

            return $this->findByCompanyIdAndDocId($companyId, $docId);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * get user company by companyId
     *
     * @param int $companyId
     * @param int $docId
     * @return \Illuminate\Support\Collection
     */
    public function findByCompanyIdAndDocId($companyId, $docId)
    {
        try {
            $userCompanyDocDetails = CompanyDoc::select('company_id', 'doc_id')->where('company_id', $companyId)->where('doc_id', $docId)->first();
            $cacheKey = 'user-company-doc:' . $docId;
            Redis::setex($cacheKey, 60 * 60 * 2, json_encode($userCompanyDocDetails));
            return $userCompanyDocDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
