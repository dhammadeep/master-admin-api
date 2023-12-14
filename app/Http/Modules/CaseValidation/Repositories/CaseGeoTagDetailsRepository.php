<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Models\CaseGeoTagDetails;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\CaseGeoTagDetailsRepoInterface;

class CaseGeoTagDetailsRepository implements CaseGeoTagDetailsRepoInterface
{
    /**
     * Get the case-geo Tag details from the database.
     *
     * @param int $caseId
     * @return \Illuminate\Support\Collection
     */
    public function getGeoTagDetails(int $caseId)
    {
        try {
            $caseGeoTagDetails = CaseGeoTagDetails::where('case_id', $caseId)->first();
            if(is_null($caseGeoTagDetails)){
                throw new Exception;
            }

            return $caseGeoTagDetails;

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
