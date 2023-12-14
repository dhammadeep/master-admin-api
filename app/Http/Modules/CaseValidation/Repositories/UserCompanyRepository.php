<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use App\Http\Modules\CaseValidation\Models\UserCompany;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\UserCompanyRepoInterface;

class UserCompanyRepository implements UserCompanyRepoInterface
{

    /**
     * get cached user company details, to check whether company id and user id is linked or not
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function findByUserIdCached(int $userId)
    {
        //Redis::flushDB();
        try {
            $cacheKey = 'user-company:' . $userId;

            if ($userCompanyDetails = Redis::get($cacheKey)) {

                return $userCompanyDetails = json_decode($userCompanyDetails);
            }

            return $this->findByUserId($userId);

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * get company details by user id
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function findByUserId(int $userId)
    {
        try {
            $userCompanyDetails = UserCompany::select('company_id')->where('user_id',$userId)->first();

            $cacheKey = 'user-company:' . $userId;
            Redis::setex($cacheKey, 60*60*2, json_encode($userCompanyDetails));

            return $userCompanyDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

}
