<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use App\Http\Modules\CaseValidation\Models\UserKyc;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Requests\BasicKycFormRequest;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\UserKycRepoInterface;

class UserKycRepository implements UserKycRepoInterface
{

    /**
     * get cached case details by location id
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function findByUserIdCached(int $userId)
    {
        //Redis::flushDB();
        try {
            $cacheKey = 'userKyc:' . $userId;

            if ($userKycDetails = Redis::get($cacheKey)) {

                $userKycDetails = json_decode($userKycDetails);

               if(!empty($userKycDetails) && $userKycDetails->location_id){
                    return $userKycDetails;
               }else{
                    return $this->findByUserId($userId);
               }
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
     * get case details by case id
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function findByUserId(int $userId)
    {
        try {
            $userKycDetails = UserKyc::select('id','user_id','location_id')->where('user_id',$userId)->first();

            $cacheKey = 'userKyc:' . $userId;
            Redis::setex($cacheKey, 60*60*2, json_encode($userKycDetails));

            return $userKycDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Check user location duplication.
     *
     * @param int $userId
     * @param int $locationId
     * @return \Illuminate\Support\Collection
     */
    public function checkUserLocationExistOrNot(int $userId,int $locationId)
    {
        try {
            // Perform the check in the database and return the result
            return UserKyc::select('id','location_id')->where('user_id','!=',$userId)->where('location_id',$locationId)->exists();

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

     /**
     * Create a new user kyc entry in the database and return the ID
     *
     * @param BasicKycFormRequest $basicKycFormRequest
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function insertUpdateUserKyc(BasicKycFormRequest $basicKycFormRequest,int $userId)
    {
        //insert ot update location
        try {
            $userKyc = UserKyc::updateOrCreate(
                ['user_id' => $userId],
                [
                'full_name' => $basicKycFormRequest->userFullName,
                'dob' => $basicKycFormRequest->dob,
                'gender' => $basicKycFormRequest->gender,
                'aadhar' => $basicKycFormRequest->aadhar,
                'pan' => $basicKycFormRequest->pan,
                'location_id' => $basicKycFormRequest->locationId
                ]
            );

            return $userKyc->id;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
