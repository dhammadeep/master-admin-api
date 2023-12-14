<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Models\CaseGeoPlottingDetails;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\CaseGeoPlottingDetailsRepoInterface;

class CaseGeoPlottingDetailsRepository implements CaseGeoPlottingDetailsRepoInterface
{
    /**
     * Get the case-geo plotting details from the database.
     *
     * @param int $caseId
     * @return \Illuminate\Support\Collection
     */
    public function getGeoPlottingDetails(int $caseId)
    {
        try {
            $geoPlottingDetails = CaseGeoPlottingDetails::where('case_id', $caseId)->first();
           // dd($geoPlottingDetails);
            if(is_null($geoPlottingDetails)){
                throw new Exception;
            }
            return $geoPlottingDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
