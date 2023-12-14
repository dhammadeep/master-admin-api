<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Models\CaseWarehouseDetails;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\CaseWarehouseDetailsRepoInterface;

class CaseWarehouseDetailsRepository implements CaseWarehouseDetailsRepoInterface
{
    /**
     * Get the warehouse doc form details from the database.
     *
     * @param int $caseId
     * @return \Illuminate\Support\Collection
     */
    public function getWarehouseDocFormDetails(int $caseId)
    {
        try {
            $caseWarehouseDetails = CaseWarehouseDetails::where('case_id', $caseId)->first();
            if(is_null($caseWarehouseDetails)){
                throw new Exception;
            }
            return $caseWarehouseDetails;

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
