<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Models\CaseUserBankAccountDetails;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\CaseUserBankAccountDetailsRepoInterface;

class CaseUserBankAccountDetailsRepository implements CaseUserBankAccountDetailsRepoInterface
{
    /**
     * Get the case-user company form details from the database.
     *
     * @param int $caseId
     * @return \Illuminate\Support\Collection
     */
    public function getUserBankAccountFormDetails(int $userId)
    {
        try {
            $bankAccountDetails = CaseUserBankAccountDetails::where('user_id', $userId)->first();

            if(is_null($bankAccountDetails)){
                throw new Exception;
            }

            return $bankAccountDetails;

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
