<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Models\BasicKycDetails;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\BasicKycDetailsRepoInterface;

class BasicKycDetailsRepository implements BasicKycDetailsRepoInterface
{
    /**
     * Get the basic KYC form details from the database.
     *
     * @param int $caseId
     * @return \Illuminate\Support\Collection
     */
    public function getBasicKycFormDetails(int $userId)
    {
        try {
            $basicKycDetails = BasicKycDetails::where('user_id', $userId)->first();
           /* if(is_null($basicKycDetails)){
                throw new Exception;
            }*/
            return $basicKycDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
