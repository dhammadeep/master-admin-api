<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use App\Http\Modules\CaseValidation\Models\FarmDoc;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Requests\DocumentApprovalRequest;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\FarmDocRepoInterface;

class FarmDocRepository implements FarmDocRepoInterface
{
    /**
     * get cached user bank account details, to check whether case bank account id is exist or not
     *
     * @param int $farmId
     * @param int $docId
     * @return \Illuminate\Support\Collection
     */
    public function findByFarmIdAndDocIdCached($farmId, $docId)
    {
        //Redis::flushDB();
        try {
            $cacheKey = 'farm-doc:' . $docId;

            if ($farmDocDetails = Redis::get($cacheKey)) {

                return $farmDocDetails = json_decode($farmDocDetails);
            }

            return $this->findByFarmIdAndDocId($farmId, $docId);
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
     * @param int $farmId
     * @param int $docId
     * @return \Illuminate\Support\Collection
     */
    public function findByFarmIdAndDocId($farmId, $docId)
    {
        try {
            $farmDocDetails = FarmDoc::select('farm_id', 'doc_id')->where('farm_id', $farmId)->where('doc_id', $docId)->first();

            $cacheKey = 'farm-doc:' . $docId;
            Redis::setex($cacheKey, 60 * 60 * 2, json_encode($farmDocDetails));

            return $farmDocDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
