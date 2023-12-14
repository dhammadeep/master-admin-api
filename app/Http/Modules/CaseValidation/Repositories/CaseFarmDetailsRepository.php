<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Models\CaseFarmDetails;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\CaseFarmDetailsRepoInterface;

class CaseFarmDetailsRepository implements CaseFarmDetailsRepoInterface
{
    /**
     * Get the land doc form details from the database.
     *
     * @param int $caseId
     * @return \Illuminate\Support\Collection
     */
    public function getLandDocFormDetails(int $caseId)
    {
        try {
            $caseFarmDetails =  CaseFarmDetails::where('case_id', $caseId)->first();
            if(is_null($caseFarmDetails)){
                throw new Exception;
            }
            return $caseFarmDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
