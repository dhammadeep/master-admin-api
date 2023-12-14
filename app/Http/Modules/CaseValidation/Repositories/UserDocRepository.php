<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use App\Http\Modules\CaseValidation\Models\UserDoc;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Requests\DocumentApprovalRequest;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\UserDocRepoInterface;

class UserDocRepository implements UserDocRepoInterface
{
    /**
     * get cached user doc details, to check whether case user id and doc id is exist or not
     *
     * @param int $userId
     * @param int $docId
     * @return \Illuminate\Support\Collection
     */
    public function findByUserIdAndDocIdCached($userId,$docId)
    {
        //Redis::flushDB();
        try {
            $cacheKey = 'user-doc:' .$docId;

            if ($userDocDetails = Redis::get($cacheKey)) {

                return $userDocDetails = json_decode($userDocDetails);
            }

            return $this->findByUserIdAndDocId($userId,$docId);

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * get user doc details by user id and doc id
     *
     * @param int $userId
     * @param int $docId
     * @return \Illuminate\Support\Collection
     */
    public function findByUserIdAndDocId($userId,$docId)
    {
        try {
            $userDocDetails = UserDoc::select('user_id','doc_id')->where('user_id',$userId)->where('doc_id',$docId)->first();

            $cacheKey = 'user-doc:' .$docId;
            Redis::setex($cacheKey, 60*60*2, json_encode($userDocDetails));

            return $userDocDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
