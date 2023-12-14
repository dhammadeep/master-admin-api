<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Models\CaseWarehouseStack;
use App\Http\Modules\CaseValidation\Requests\WarehouseStackFormRequest;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\CaseWarehouseStackRepoInterface;

class CaseWarehouseStackRepository implements CaseWarehouseStackRepoInterface
{

    /**
     * get warehouse stack details.
     *
     * @param int $caseId
     * @return \Illuminate\Support\Collection
     */
    public function getStackDetails(int $caseWarehouseId)
    {
        try {
            return CaseWarehouseStack::where('case_warehouse_id', $caseWarehouseId)->get();

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Store warehouse stack form details.
     *
     * @param int $caseId
     * @return \Illuminate\Support\Collection
     */
    public function checkUniqueStackNoWithCaseWarehouseId(int $caseWarehouseId,int $stackNo)
    {
        try {
            return CaseWarehouseStack::where('case_warehouse_id', $caseWarehouseId)->where('stack_no',$stackNo)->exists();

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Store warehouse stack form details.
     *
     * @param int $caseId
     * @return \Illuminate\Support\Collection
     */
    public function storeStackDetails(WarehouseStackFormRequest $warehouseStackFormRequest, int $caseWarehouseId)
    {
        //insert ot update location
        try {
           /* CaseWarehouseStack::updateOrCreate(
                ['case_warehouse_id' => $caseWarehouseId],
                [
                'stack_no' => $warehouseStackFormRequest->stackNo,
                'no_of_package' => $warehouseStackFormRequest->noOfPackage,
                'package_uom_id' => $warehouseStackFormRequest->packageUomId,
                'unit_package_size' => $warehouseStackFormRequest->unitPackageSize,
                'unit_package_size_uom_id' => $warehouseStackFormRequest->unitPackageSizeUomId
                ]
            );*/

            CaseWarehouseStack::Create(
                [
                'case_warehouse_id' => $caseWarehouseId,
                'stack_no' => $warehouseStackFormRequest->stackNo,
                'no_of_package' => $warehouseStackFormRequest->noOfPackage,
                'package_uom_id' => $warehouseStackFormRequest->packageUomId,
                'unit_package_size' => $warehouseStackFormRequest->unitPackageSize,
                'unit_package_size_uom_id' => $warehouseStackFormRequest->unitPackageSizeUomId
                ]
            );

            return true;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
