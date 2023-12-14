<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Models\CaseUserCompanyDetails;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\CaseUserCompanyDetailsRepoInterface;

class CaseUserCompanyDetailsRepository implements CaseUserCompanyDetailsRepoInterface
{
    /**
     * Get the case-user company form details from the database.
     *
     * @param int $caseId
     * @return \Illuminate\Support\Collection
     */
    public function getCompanyFormDetails(int $userId)
    {
        try {

            $companyDetails = CaseUserCompanyDetails::where('user_id', $userId)->first();

            if(is_null($companyDetails)){
                throw new Exception;
            }

            return $companyDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
